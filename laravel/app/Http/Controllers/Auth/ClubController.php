<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ResponsableClub;
use App\Models\User;
use App\Models\ResponsableInvitation as ResponsableInvitationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Mail\ResponsableInvitation as ResponsableInvitationMail;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::with('responsable')->get();

        // Collect club IDs that currently have a pending responsable invitation
        $pendingClubIds = ResponsableInvitationModel::where('status', 'pending')->pluck('club_id')->unique()->toArray();

        return view('clubs.index', compact('clubs', 'pendingClubIds'));
    }

    public function create()
    {
        if (!Auth::check()) {
            abort(403, 'Veuillez vous connecter pour accéder à cette page.');
        }

        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent créer des clubs.');
        }

        // Récupérer les utilisateurs qui ne sont pas déjà responsables d'un club
        $responsableIds = ResponsableClub::pluck('UTI_ID')->filter()->unique()->toArray();
        $availableUsers = User::whereNotIn('UTI_ID', $responsableIds)->get();

        return view('clubs.create', compact('availableUsers'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent créer des clubs.');
        }

        // Validation conditionnelle: si un utilisateur existant est sélectionné, on ne demande pas les champs du nouveau responsable
        $rules = [
            'CLU_NOM' => 'required|string|max:50',
            'CLU_RUE' => 'required|string|max:100',
            'CLU_CODE_POSTAL' => 'required|string|max:6',
            'CLU_VILLE' => 'required|string|max:50',
        ];

        if ($request->filled('selected_responsable_id')) {
            $rules['selected_responsable_id'] = 'required|exists:vik_utilisateur,UTI_ID';
        } else {
            $rules['RESP_NOM'] = 'required|string|max:50';
            $rules['RESP_PRENOM'] = 'required|string|max:50';
            $rules['RESP_EMAIL'] = 'required|email|max:100';
            $rules['RESP_NOM_UTILISATEUR'] = 'required|string|max:255|unique:vik_responsable_club,UTI_NOM_UTILISATEUR';
        }

        $request->validate($rules);

        $club = Club::create([
            'CLU_NOM' => $request->CLU_NOM,
            'CLU_RUE' => $request->CLU_RUE,
            'CLU_CODE_POSTAL' => $request->CLU_CODE_POSTAL,
            'CLU_VILLE' => $request->CLU_VILLE,
        ]);

        $responsableId = null;

        if ($request->filled('selected_responsable_id')) {
            // Utilisateur existant choisi
            $selectedId = $request->selected_responsable_id;

            // Vérifier qu'il n'est pas déjà responsable
            if (ResponsableClub::where('UTI_ID', $selectedId)->exists()) {
                return back()->withErrors(['selected_responsable_id' => "Cet utilisateur est déjà responsable d'un club."])->withInput();
            }

            $user = User::findOrFail($selectedId);

            // Create an invitation for the existing user instead of directly promoting them
            $token = bin2hex(random_bytes(16));
            $invitation = ResponsableInvitationModel::create([
                'club_id' => $club->CLU_ID,
                'user_id' => $user->UTI_ID,
                'token' => $token,
                'status' => 'pending',
            ]);

            // Send invitation email (synchronous for now)
            try {
                Mail::to($user->UTI_EMAIL)->send(new ResponsableInvitationMail($club, $token, $user, false));
            } catch (\Throwable $e) {
                // Log or ignore mail failures for now
            }

            // Make the invited user available to the created view / mailbox
            session(['invited_user_id' => $user->UTI_ID, 'invitation_id' => $invitation->id]);

            $responsableId = null; // still pending until user accepts
        } else {
            // Créer un nouvel utilisateur et responsable
            $responsableId = User::max('UTI_ID') + 1; // Générer un nouvel ID basé sur vik_utilisateur

            // Créer d'abord l'utilisateur de base
            $user = User::create([
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

            // generate a token for the mailbox/confirmation view for the new responsable flow
            $token = bin2hex(random_bytes(16));

            // Persist an invitation so the new-user flow can be accepted/refused like existing-user invitations
            $invitation = ResponsableInvitationModel::create([
                'club_id' => $club->CLU_ID,
                'user_id' => $user->UTI_ID,
                'token' => $token,
                'status' => 'pending',
            ]);

            // Send an invitation email to the newly created user prompting them to complete registration
            try {
                Mail::to($user->UTI_EMAIL)->send(new ResponsableInvitationMail($club, $token, $user, true));
            } catch (\Throwable $e) {
                // ignore or log mail failures for now
            }

            // Make the invited (newly created) user available to the created view / mailbox
            session(['invited_user_id' => $user->UTI_ID, 'invitation_id' => $invitation->id]);
        }

        // Ensure we use the same token used above (for existing-invitation flow it's already set)
        if (!isset($token)) {
            $token = bin2hex(random_bytes(16));
        }

        return redirect()->route('clubs.created', [
            'club' => $club->CLU_ID,
            'token' => $token
        ]);
    }

    public function showCreated($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        // Try to pull invited user from session (set when creating with an existing user)
        $invitedUser = null;
        if (session()->has('invited_user_id')) {
            $invitedUser = User::find(session()->get('invited_user_id'));
        }

        // If there's a pending invitation matching the token, pass it as well
        $invitation = ResponsableInvitationModel::where('club_id', $clubId)->where('token', $token)->where('status', 'pending')->first();

        return view('clubs.created', compact('club', 'token', 'invitedUser', 'invitation'));
    }

    public function showFakeMailbox($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        // Look for a pending invitation by token and club
        $invitation = ResponsableInvitationModel::where('club_id', $clubId)->where('token', $token)->where('status', 'pending')->first();

        $invitedUser = null;
        if ($invitation) {
            $invitedUser = $invitation->user;
        } elseif (session()->has('invited_user_id')) {
            $invitedUser = User::find(session()->get('invited_user_id'));
        } elseif ($club->responsable) {
            // If club already has a responsable (created immediately for new user flow), use it
            $invitedUser = User::where('UTI_ID', $club->responsable->UTI_ID)->first();
        }

        if (!$invitedUser && !$invitation) {
            abort(404, 'Responsable non trouvé');
        }

        return view('emails.fake-mailbox', compact('club', 'token', 'invitedUser', 'invitation'));
    }

    public function showResponsableRegistration($clubId, $token)
    {
        $club = Club::with('responsable')->findOrFail($clubId);

        // Try to locate a persistent invitation (for existing-user invitations)
        $invitation = ResponsableInvitationModel::where('club_id', $clubId)
            ->where('token', $token)
            ->where('status', 'pending')
            ->first();

        // Resolve the invited user from the invitation, session (new-user flow) or the club responsable
        $user = null;
        if ($invitation) {
            $user = $invitation->user;
        } elseif (session()->has('invited_user_id')) {
            $user = User::find(session()->get('invited_user_id'));
        } elseif ($club->responsable) {
            $user = User::where('UTI_ID', $club->responsable->UTI_ID)->first();
        }

        if (!$user) {
            abort(404, 'Utilisateur invité introuvable');
        }

        // If we don't have a persistent invitation, create a lightweight object so the view can show the token
        if (!$invitation) {
            $invitation = (object) ['token' => $token];
        }

        return view('auth.responsable-register', compact('club', 'token', 'user', 'invitation'));
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

        // If there's a pending invitation associated with this club and token, mark it accepted
        if ($request->filled('invitation_token')) {
            $invitation = ResponsableInvitationModel::where('club_id', $request->club_id)
                ->where('token', $request->invitation_token)
                ->where('status', 'pending')
                ->first();

            if ($invitation) {
                $invitation->status = 'accepted';
                $invitation->accepted_at = now();
                $invitation->save();
            }
        }

        return redirect('/')->with('success', 'Votre inscription a été finalisée avec succès ! Bienvenue.');
    }

    public function show(Club $club)
    {
        $club->load('responsable');

        // Determine if there's still a pending invitation for this club
        $pending = ResponsableInvitationModel::where('club_id', $club->CLU_ID)->where('status', 'pending')->exists();

        return view('clubs.show', compact('club', 'pending'));
    }

    public function edit(Club $club)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $club->load('responsable');

        $availableUsers = User::whereDoesntHave('clubs')
            ->whereDoesntHave('administrateur')
            ->when($club->responsable, function ($query) use ($club) {
                return $query->orWhere('UTI_ID', $club->responsable->UTI_ID);
            })
            ->get();

        $userData = $availableUsers->map(function ($user) {
            return [
                'id' => $user->UTI_ID,
                'nom' => $user->UTI_NOM,
                'prenom' => $user->UTI_PRENOM,
                'email' => $user->UTI_EMAIL,
                'nom_utilisateur' => $user->UTI_NOM_UTILISATEUR,
            ];
        });

        return view('clubs.edit', compact('club', 'availableUsers', 'userData'));
    }

    public function update(Request $request, Club $club)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $club->load('responsable');

        $rules = [
            'CLU_NOM' => 'required|string|max:100',
            'CLU_RUE' => 'required|string|max:255',
            'CLU_CODE_POSTAL' => 'required|string|max:10',
            'CLU_VILLE' => 'required|string|max:100',
        ];

        if ($request->filled('selected_responsable_id')) {
            $rules['selected_responsable_id'] = 'required|exists:vik_utilisateur,UTI_ID';
        } else {
            $rules['RESP_NOM'] = 'required|string|max:50';
            $rules['RESP_PRENOM'] = 'required|string|max:50';
            $rules['RESP_EMAIL'] = 'required|email|max:100';
            $rules['RESP_NOM_UTILISATEUR'] = 'required|string|max:255|unique:vik_responsable_club,UTI_NOM_UTILISATEUR';
        }

        $request->validate($rules);

        $club->update([
            'CLU_NOM' => $request->CLU_NOM,
            'CLU_RUE' => $request->CLU_RUE,
            'CLU_CODE_POSTAL' => $request->CLU_CODE_POSTAL,
            'CLU_VILLE' => $request->CLU_VILLE,
        ]);

        $selectedId = $request->selected_responsable_id;

        if ($selectedId) {
            // Utilisateur existant sélectionné
            $user = User::findOrFail($selectedId);

            if (!$club->responsable || $selectedId != $club->responsable->UTI_ID) {
                // Vérifier que le nouvel utilisateur n'est pas déjà responsable
                if (ResponsableClub::where('UTI_ID', $selectedId)->exists()) {
                    return back()->withErrors(['selected_responsable_id' => 'Cet utilisateur est déjà responsable d\'un autre club.'])->withInput();
                }

                // Supprimer l'ancien responsable s'il existe
                if ($club->responsable) {
                    $club->responsable->delete();
                }

                // Créer un nouveau responsable avec les données de l'utilisateur
                ResponsableClub::create([
                    'UTI_ID' => $selectedId,
                    'CLU_ID' => $club->CLU_ID,
                    'UTI_NOM' => $user->UTI_NOM,
                    'UTI_PRENOM' => $user->UTI_PRENOM,
                    'UTI_EMAIL' => $user->UTI_EMAIL,
                    'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                    'UTI_RUE' => $user->UTI_RUE,
                    'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                    'UTI_VILLE' => $user->UTI_VILLE,
                    'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                    'UTI_LICENCE' => $user->UTI_LICENCE,
                    'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                    'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
                ]);

                // Recharger la relation
                $club->load('responsable');
            }
            // Si c'est le même, rien à faire
        } else {
            // Créer un nouvel utilisateur et responsable
            // Générer un nouvel ID pour l'utilisateur
            $newUserId = User::max('UTI_ID') + 1;

            // Créer l'utilisateur
            $user = User::create([
                'UTI_ID' => $newUserId,
                'UTI_NOM' => $request->RESP_NOM,
                'UTI_PRENOM' => $request->RESP_PRENOM,
                'UTI_EMAIL' => $request->RESP_EMAIL,
                'UTI_NOM_UTILISATEUR' => $request->RESP_NOM_UTILISATEUR,
            ]);

            // Créer le responsable
            ResponsableClub::create([
                'UTI_ID' => $newUserId,
                'CLU_ID' => $club->CLU_ID,
                'UTI_NOM' => $request->RESP_NOM,
                'UTI_PRENOM' => $request->RESP_PRENOM,
                'UTI_EMAIL' => $request->RESP_EMAIL,
                'UTI_NOM_UTILISATEUR' => $request->RESP_NOM_UTILISATEUR,
            ]);

            // Recharger la relation
            $club->load('responsable');
        }

        return redirect()->route('clubs.index')->with('success', 'Club mis à jour avec succès.');
    }

    public function destroy(Club $club)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent supprimer des clubs.');
        }

        // Charger la relation responsable
        $club->load('responsable');

        // Si le club a un responsable, le supprimer de la table vik_responsable_club
        if ($club->responsable) {
            $club->responsable->delete();
        }

        // Supprimer les données associées aux raids du club
        $raidIds = $club->raids()->pluck('RAI_ID');
        $coureurIds = DB::table('vik_coureur')->where('CLU_ID', $club->CLU_ID)->pluck('UTI_ID');

        if ($raidIds->isNotEmpty()) {
            // Supprimer les résultats
            DB::table('vik_resultat')->whereIn('RAI_ID', $raidIds)->delete();
            // Supprimer les appartenances
            DB::table('vik_appartient')->whereIn('RAI_ID', $raidIds)->orWhereIn('UTI_ID', $coureurIds)->delete();
        }

        // Supprimer les coureurs associés au club
        DB::table('vik_coureur')->where('CLU_ID', $club->CLU_ID)->delete();

        if ($raidIds->isNotEmpty()) {
            // Supprimer les équipes
            DB::table('vik_equipe')->whereIn('RAI_ID', $raidIds)->delete();
            // Supprimer les courses
            DB::table('vik_course')->whereIn('RAI_ID', $raidIds)->delete();
        }

        // Supprimer les raids associés au club
        $club->raids()->delete();

        // Supprimer le club
        $club->delete();

        return redirect()->route('clubs.index')->with('success', 'Le club a été supprimé avec succès. Le responsable est maintenant un utilisateur normal.');
    }

    /**
     * Affiche la page d'invitation (GET) pour les invitations aux utilisateurs existants
     */
    public function showInvitation($clubId, $userId, $token)
    {
        $club = Club::findOrFail($clubId);

        $invitation = ResponsableInvitationModel::where('club_id', $clubId)
            ->where('user_id', $userId)
            ->where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable');
        }

        $user = $invitation->user;

        // If the accept URL is used and user is not authenticated, redirect them to login and set intended URL
        if (!Auth::check() && request()->route()->getName() === 'responsable.invitation.accept.show') {
            session(['url.intended' => route('responsable.invitation.accept.show', ['club_id' => $clubId, 'user_id' => $userId, 'token' => $token])]);
            return redirect()->route('login')->with('info', "Veuillez vous connecter pour accepter l'invitation.");
        }

        // Do not auto-accept on GET requests — show the invitation page and require the POST accept action.

        return view('auth.responsable-invitation', compact('club', 'user', 'token'));
    }

    /**
     * Redirect to login and set intended to the POST accept route so the user completes login first
     */
    public function redirectToLoginForInvitation($clubId, $userId, $token)
    {
        $invitation = ResponsableInvitationModel::where('club_id', $clubId)
            ->where('user_id', $userId)
            ->where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable');
        }

        // set intended to the GET invitation page so Laravel redirects back to it after login
        // If the user is already authenticated as the invited user, show the invitation page (no auto-accept).
        if (Auth::check() && Auth::user()->UTI_ID == $userId) {
            return redirect()->route('responsable.invitation.accept.show', ['club_id' => $clubId, 'user_id' => $userId, 'token' => $token]);
        }

        // Otherwise force login and set intended to return to the acceptance-after-login route
        session(['url.intended' => route('responsable.invitation.accept.after_login', ['club_id' => $clubId, 'user_id' => $userId, 'token' => $token])]);
        return redirect()->route('login')->with('info', "Veuillez vous connecter pour accepter l'invitation.");
    }

    /**
     * Accept automatically after login — intended target after the login redirect.
     * This route will perform the same checks as `acceptInvitation` but runs on GET
     * immediately after the invited user logs in.
     */
    public function acceptAfterLogin(Request $request, $clubId, $userId, $token)
    {
        if (!Auth::check()) {
            // Not authenticated: redirect to login (shouldn't normally happen because intended should point here)
            session(['url.intended' => route('responsable.invitation.accept.after_login', ['club_id' => $clubId, 'user_id' => $userId, 'token' => $token])]);
            return redirect()->route('login')->with('info', "Veuillez vous connecter pour accepter l'invitation.");
        }

        // Only the invited user may auto-accept after login
        if (Auth::user()->UTI_ID != $userId) {
            abort(403, 'Vous n\'êtes pas autorisé à accepter cette invitation.');
        }

        $invitation = ResponsableInvitationModel::where('club_id', $clubId)
            ->where('user_id', $userId)
            ->where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable');
        }

        // Prevent duplicate responsibilities
        if (ResponsableClub::where('CLU_ID', $clubId)->exists()) {
            return redirect()->route('clubs.index')->with('error', 'Ce club a déjà un responsable.');
        }

        if (ResponsableClub::where('UTI_ID', $userId)->exists()) {
            return redirect()->route('clubs.index')->with('error', 'Cet utilisateur est déjà responsable d\'un club.');
        }

        $user = User::findOrFail($userId);

        try {
            DB::beginTransaction();

            if (ResponsableClub::where('CLU_ID', $clubId)->lockForUpdate()->exists()) {
                DB::rollBack();
                return redirect()->route('clubs.index')->with('error', 'Ce club a déjà un responsable.');
            }

            if (ResponsableClub::where('UTI_ID', $userId)->lockForUpdate()->exists()) {
                DB::rollBack();
                return redirect()->route('clubs.index')->with('error', 'Cet utilisateur est déjà responsable d\'un club.');
            }

            ResponsableClub::create([
                'UTI_ID' => $user->UTI_ID,
                'CLU_ID' => $clubId,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);

            $invitation->status = 'accepted';
            $invitation->accepted_at = now();
            $invitation->save();

            DB::commit();

            return redirect()->route('clubs.index')->with('success', 'Invitation acceptée. Vous êtes maintenant responsable du club.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('clubs.index')->with('error', 'Conflit lors de l\'acceptation — l\'opération a déjà été effectuée.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Accepter une invitation (POST)
     */
    public function acceptInvitation(Request $request, $clubId, $userId, $token)
    {
        $invitation = ResponsableInvitationModel::where('club_id', $clubId)
            ->where('user_id', $userId)
            ->where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable');
        }

        // If not logged in, redirect to login page and set intended to the GET invitation page
        if (!Auth::check()) {
            session(['url.intended' => route('responsable.invitation.accept.show', ['club_id' => $clubId, 'user_id' => $userId, 'token' => $token])]);
            return redirect()->route('login')->with('info', "Veuillez vous connecter pour accepter l'invitation.");
        }

        // Ensure the logged-in user is the invited user (or admin)
        if (Auth::user()->UTI_ID != $userId && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à accepter cette invitation.');
        }

        // Prevent duplicate responsibilities
        if (ResponsableClub::where('CLU_ID', $clubId)->exists()) {
            return redirect()->route('clubs.index')->with('error', 'Ce club a déjà un responsable.');
        }

        if (ResponsableClub::where('UTI_ID', $userId)->exists()) {
            return redirect()->route('clubs.index')->with('error', 'Cet utilisateur est déjà responsable d\'un club.');
        }

        $user = User::findOrFail($userId);

        try {
            DB::beginTransaction();

            if (ResponsableClub::where('CLU_ID', $clubId)->lockForUpdate()->exists()) {
                DB::rollBack();
                return redirect()->route('clubs.index')->with('error', 'Ce club a déjà un responsable.');
            }

            if (ResponsableClub::where('UTI_ID', $userId)->lockForUpdate()->exists()) {
                DB::rollBack();
                return redirect()->route('clubs.index')->with('error', 'Cet utilisateur est déjà responsable d\'un club.');
            }

            ResponsableClub::create([
                'UTI_ID' => $user->UTI_ID,
                'CLU_ID' => $clubId,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);

            $invitation->status = 'accepted';
            $invitation->accepted_at = now();
            $invitation->save();

            DB::commit();

            return redirect()->route('clubs.index')->with('success', 'Invitation acceptée. Vous êtes maintenant responsable du club.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('clubs.index')->with('error', 'Conflit lors de l\'acceptation — l\'opération a déjà été effectuée.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Refuser une invitation (POST)
     */
    public function refuseInvitation(Request $request, $clubId, $userId, $token)
    {
        $invitation = ResponsableInvitationModel::where('club_id', $clubId)
            ->where('user_id', $userId)
            ->where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable');
        }

        if (!Auth::check()) {
            session(['url.intended' => route('responsable.invitation.show', ['club_id' => $clubId, 'user_id' => $userId, 'token' => $token])]);
            return redirect()->route('login')->with('info', "Veuillez vous connecter pour refuser l'invitation.");
        }

        if (Auth::user()->UTI_ID != $userId && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à refuser cette invitation.');
        }

        $invitation->status = 'refused';
        $invitation->refused_at = now();
        $invitation->save();

        // Notify admin or set session for admin notification if required
        session(['refused_invitation' => $invitation->id]);

        return redirect('/')->with('success', 'Vous avez refusé l\'invitation. Merci d\'avoir répondu.');
    }

    /**
     * Accept an invitation on behalf of the user (admin action)
     */
    public function adminAcceptInvitation(Request $request, $clubId, $userId, $token)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $invitation = ResponsableInvitationModel::where('club_id', $clubId)
            ->where('user_id', $userId)
            ->where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable');
        }

        $user = $invitation->user;

        try {
            DB::beginTransaction();

            if (ResponsableClub::where('CLU_ID', $clubId)->lockForUpdate()->exists()) {
                DB::rollBack();
                return redirect()->route('clubs.index')->with('error', 'Ce club a déjà un responsable.');
            }

            if (ResponsableClub::where('UTI_ID', $userId)->lockForUpdate()->exists()) {
                DB::rollBack();
                return redirect()->route('clubs.index')->with('error', 'Cet utilisateur est déjà responsable d\'un club.');
            }

            ResponsableClub::create([
                'UTI_ID' => $user->UTI_ID,
                'CLU_ID' => $clubId,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);

            $invitation->status = 'accepted';
            $invitation->accepted_at = now();
            $invitation->save();

            DB::commit();

            return redirect()->route('clubs.index')->with('success', 'Invitation acceptée au nom de l\'utilisateur.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('clubs.index')->with('error', 'Conflit lors de l\'acceptation — l\'opération a déjà été effectuée.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $invitation->status = 'accepted';
        $invitation->accepted_at = now();
        $invitation->save();

        return redirect()->route('clubs.index')->with('success', 'Invitation acceptée au nom de l\'utilisateur.');
    }
}
