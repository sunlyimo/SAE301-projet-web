<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ResponsableClub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::with('responsable')->get();
        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        if (!Auth::check()) {
            abort(403, 'Veuillez vous connecter pour accéder à cette page.');
        }

        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent créer des clubs.');
        }

        return view('clubs.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent créer des clubs.');
        }

        $request->validate([
            'CLU_NOM' => 'required|string|max:50',
            'CLU_RUE' => 'required|string|max:100',
            'CLU_CODE_POSTAL' => 'required|string|max:6',
            'CLU_VILLE' => 'required|string|max:50',
            'RESP_NOM' => 'required|string|max:50',
            'RESP_PRENOM' => 'required|string|max:50',
            'RESP_EMAIL' => 'required|email|max:100',
            'RESP_NOM_UTILISATEUR' => 'required|string|max:255|unique:vik_responsable_club,UTI_NOM_UTILISATEUR',
        ]);

        $club = Club::create([
            'CLU_NOM' => $request->CLU_NOM,
            'CLU_RUE' => $request->CLU_RUE,
            'CLU_CODE_POSTAL' => $request->CLU_CODE_POSTAL,
            'CLU_VILLE' => $request->CLU_VILLE,
        ]);

        // Créer le responsable du club
        $responsableId = User::max('UTI_ID') + 1; // Générer un nouvel ID basé sur vik_utilisateur

        // Créer d'abord l'utilisateur de base
        User::create([
            'UTI_ID' => $responsableId,
            'UTI_NOM' => $request->RESP_NOM,
            'UTI_PRENOM' => $request->RESP_PRENOM,
            'UTI_EMAIL' => $request->RESP_EMAIL,
            'UTI_NOM_UTILISATEUR' => $request->RESP_NOM_UTILISATEUR,
        ]);

        ResponsableClub::create([
            'UTI_ID' => $responsableId,
            'CLU_ID' => $club->CLU_ID,
            'UTI_NOM' => $request->RESP_NOM,
            'UTI_PRENOM' => $request->RESP_PRENOM,
            'UTI_EMAIL' => $request->RESP_EMAIL,
            'UTI_NOM_UTILISATEUR' => $request->RESP_NOM_UTILISATEUR,
        ]);

        // Générer un token fictif pour simuler l'invitation
        $token = md5($club->CLU_ID . $responsableId . time());

        return redirect()->route('clubs.created', [
            'club' => $club->CLU_ID,
            'token' => $token
        ]);
    }

    public function showCreated($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        return view('clubs.created', compact('club', 'token'));
    }

    public function showFakeMailbox($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        if (!$club->responsable) {
            abort(404, 'Responsable non trouvé');
        }

        return view('emails.fake-mailbox', compact('club', 'token'));
    }

    public function showResponsableRegistration($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        if (!$club->responsable) {
            abort(404, 'Responsable non trouvé');
        }

        return view('auth.responsable-register', compact('club', 'token'));
    }

    public function quickValidateResponsable($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        if (!$club->responsable) {
            abort(404, 'Responsable non trouvé');
        }

        $responsable = $club->responsable;

        // Générer des données par défaut pour la validation automatique
        $defaultData = [
            'UTI_DATE_NAISSANCE' => now()->subYears(25)->format('Y-m-d'), // Date de naissance par défaut (25 ans)
            'UTI_LICENCE' => 'AUTO' . str_pad($responsable->UTI_ID, 4, '0', STR_PAD_LEFT), // Licence automatique
            'UTI_RUE' => 'Rue à définir',
            'UTI_CODE_POSTAL' => '00000',
            'UTI_VILLE' => 'Ville à définir',
            'UTI_TELEPHONE' => '0102030405', // 10 caractères maximum
            'password' => 'TempPass123!', // Mot de passe temporaire
        ];

        // Mettre à jour le responsable avec les données par défaut
        $responsable->update($defaultData);

        // Mettre à jour l'utilisateur dans vik_utilisateur
        $user = User::find($responsable->UTI_ID);
        $user->update([
            'UTI_DATE_NAISSANCE' => $defaultData['UTI_DATE_NAISSANCE'],
            'UTI_MOT_DE_PASSE' => bcrypt($defaultData['password']),
            'UTI_RUE' => $defaultData['UTI_RUE'],
            'UTI_CODE_POSTAL' => $defaultData['UTI_CODE_POSTAL'],
            'UTI_VILLE' => $defaultData['UTI_VILLE'],
            'UTI_TELEPHONE' => $defaultData['UTI_TELEPHONE'],
            'UTI_LICENCE' => $defaultData['UTI_LICENCE'],
        ]);

        return redirect()->route('responsable.quick-validated', [
            'club' => $club->CLU_ID,
            'token' => $token
        ]);
    }

    public function refuseResponsable($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        if (!$club->responsable) {
            abort(404, 'Responsable non trouvé');
        }

        $responsable = $club->responsable;

        // Store responsable details in session before deletion so we can show them in the notification view
        $respData = null;
        if ($responsable) {
            $respData = $responsable->only(['UTI_NOM', 'UTI_PRENOM', 'UTI_EMAIL', 'UTI_ID']);
            session(['refused_responsable' => $respData]);
            $responsable->delete(); // Supprimer l'entrée du responsable qui a refusé
        }

        // Générer un token pour la notification admin
        $adminToken = md5($club->CLU_ID . ($respData['UTI_ID'] ?? '0') . 'admin' . time());

        return redirect()->route('admin.refusal-notification', [
            'club_id' => $club->CLU_ID,
            'token' => $adminToken
        ]);
    }

    public function showAdminRefusalNotification($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        // Pull refused responsable details (if any) from session and remove it from session
        $refusedResponsable = session()->pull('refused_responsable', null);

        return view('emails.admin-refusal-notification', compact('club', 'token', 'refusedResponsable'));
    }

    public function recreateClub($clubId, $token)
    {
        $club = Club::findOrFail($clubId);

        $params = [
            'CLU_NOM' => $club->CLU_NOM,
            'CLU_RUE' => $club->CLU_RUE,
            'CLU_CODE_POSTAL' => $club->CLU_CODE_POSTAL,
            'CLU_VILLE' => $club->CLU_VILLE,
        ];

        $url = route('clubs.create', $params);
        session(['url.intended' => $url]);

        return redirect('/login');
    }

    public function showQuickValidated($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        return view('auth.quick-validated', compact('club', 'token'));
    }

    public function completeResponsableRegistration(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:vik_club,CLU_ID',
            'responsable_id' => 'required|exists:vik_responsable_club,UTI_ID',
            'UTI_DATE_NAISSANCE' => 'required|date',
            'UTI_LICENCE' => 'required|string|max:15',
            'UTI_RUE' => 'required|string|max:100',
            'UTI_CODE_POSTAL' => 'required|string|max:10',
            'UTI_VILLE' => 'required|string|max:50',
            'UTI_TELEPHONE' => 'required|string|max:16',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $club = Club::findOrFail($request->club_id);
        $responsable = $club->responsable;

        if (!$responsable || $responsable->UTI_ID != $request->responsable_id) {
            abort(404, 'Responsable non trouvé');
        }

        // Mettre à jour le responsable avec les informations complètes
        $responsable->update([
            'UTI_DATE_NAISSANCE' => $request->UTI_DATE_NAISSANCE,
            'UTI_LICENCE' => $request->UTI_LICENCE,
            'UTI_RUE' => $request->UTI_RUE,
            'UTI_CODE_POSTAL' => $request->UTI_CODE_POSTAL,
            'UTI_VILLE' => $request->UTI_VILLE,
            'UTI_TELEPHONE' => $request->UTI_TELEPHONE,
            'UTI_MOT_DE_PASSE' => bcrypt($request->password),
        ]);

        // Mettre à jour l'utilisateur dans vik_utilisateur
        $user = User::find($responsable->UTI_ID);
        $user->update([
            'UTI_DATE_NAISSANCE' => $responsable->UTI_DATE_NAISSANCE,
            'UTI_MOT_DE_PASSE' => bcrypt($request->password),
            'UTI_RUE' => $responsable->UTI_RUE,
            'UTI_CODE_POSTAL' => $responsable->UTI_CODE_POSTAL,
            'UTI_VILLE' => $responsable->UTI_VILLE,
            'UTI_TELEPHONE' => $responsable->UTI_TELEPHONE,
            'UTI_LICENCE' => $responsable->UTI_LICENCE,
        ]);

        // Connecter automatiquement l'utilisateur
        Auth::login($user);

        return redirect('/')->with('success', 'Votre inscription a été finalisée avec succès ! Bienvenue.');
    }

    public function show(Club $club)
    {
        $club->load('responsable');
        return view('clubs.show', compact('club'));
    }

    public function edit(Club $club)
    {
        if (!Auth::check()) {
            abort(403, 'Veuillez vous connecter.');
        }

        if (!Auth::user()->isAdmin() && !Auth::user()->isResponsableOf($club)) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs ou le responsable du club peuvent modifier ce club.');
        }

        $club->load('responsable');

        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        if (!Auth::check()) {
            abort(403, 'Veuillez vous connecter.');
        }

        if (!Auth::user()->isAdmin() && !Auth::user()->isResponsableOf($club)) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs ou le responsable du club peuvent modifier ce club.');
        }

        $request->validate([
            'CLU_NOM' => 'required|string|max:50',
            'CLU_RUE' => 'required|string|max:100',
            'CLU_CODE_POSTAL' => 'required|string|max:6',
            'CLU_VILLE' => 'required|string|max:50',
            'RESP_NOM' => 'required|string|max:50',
            'RESP_PRENOM' => 'required|string|max:50',
            'RESP_EMAIL' => 'required|email|max:100',
            'RESP_NOM_UTILISATEUR' => 'required|string|max:255|unique:vik_responsable_club,UTI_NOM_UTILISATEUR,' . $club->responsable->UTI_ID . ',UTI_ID',
        ]);

        // Mettre à jour le club
        $club->update([
            'CLU_NOM' => $request->CLU_NOM,
            'CLU_RUE' => $request->CLU_RUE,
            'CLU_CODE_POSTAL' => $request->CLU_CODE_POSTAL,
            'CLU_VILLE' => $request->CLU_VILLE,
        ]);

        // Mettre à jour le responsable
        if ($club->responsable) {
            $club->responsable->update([
                'UTI_NOM' => $request->RESP_NOM,
                'UTI_PRENOM' => $request->RESP_PRENOM,
                'UTI_EMAIL' => $request->RESP_EMAIL,
                'UTI_NOM_UTILISATEUR' => $request->RESP_NOM_UTILISATEUR,
            ]);

            // Mettre à jour également dans la table vik_utilisateur si l'utilisateur existe
            $user = User::where('UTI_ID', $club->responsable->UTI_ID)->first();
            if ($user) {
                $user->update([
                    'UTI_NOM' => $request->RESP_NOM,
                    'UTI_PRENOM' => $request->RESP_PRENOM,
                    'UTI_EMAIL' => $request->RESP_EMAIL,
                    'UTI_NOM_UTILISATEUR' => $request->RESP_NOM_UTILISATEUR,
                ]);
            }
        }

        return redirect()->route('clubs.index')->with('success', 'Le club a été modifié avec succès.');
    }

    public function destroy(Club $club)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent supprimer des clubs.');
        }

        // Charger la relation responsable
        $club->load('responsable');

        // Si le club a un responsable, le convertir en utilisateur normal
        if ($club->responsable) {
            // Créer un utilisateur normal avec les données du responsable
            User::create([
                'UTI_NOM' => $club->responsable->UTI_NOM,
                'UTI_PRENOM' => $club->responsable->UTI_PRENOM,
                'UTI_EMAIL' => $club->responsable->UTI_EMAIL,
                'UTI_DATE_NAISSANCE' => $club->responsable->UTI_DATE_NAISSANCE,
                'UTI_MOT_DE_PASSE' => $club->responsable->UTI_MOT_DE_PASSE ?: bcrypt('password123'), // Mot de passe par défaut si null
                'UTI_NOM_UTILISATEUR' => $club->responsable->UTI_NOM_UTILISATEUR,
                'UTI_RUE' => $club->responsable->UTI_RUE,
                'UTI_CODE_POSTAL' => $club->responsable->UTI_CODE_POSTAL,
                'UTI_VILLE' => $club->responsable->UTI_VILLE,
                'UTI_TELEPHONE' => $club->responsable->UTI_TELEPHONE,
                'UTI_LICENCE' => $club->responsable->UTI_LICENCE,
            ]);

            // Supprimer le responsable de la table vik_responsable_club
            $club->responsable->delete();
        }

        // Supprimer le club
        $club->delete();

        return redirect()->route('clubs.index')->with('success', 'Le club a été supprimé avec succès. Le responsable est maintenant un utilisateur normal.');
    }
}
