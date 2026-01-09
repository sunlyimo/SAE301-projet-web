<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RaidController extends Controller
{

    private $rules = [
        'RAI_NOM' => 'required|max:50',
        'RAI_LIEU' => 'required|max:100',
        'RAI_WEB' => 'nullable|url',
        'CLU_ID' => 'required|exists:vik_club,CLU_ID',
        'UTI_ID' => 'required|exists:VIK_UTILISATEUR,UTI_ID',
        'RAI_RAID_DATE_DEBUT' => 'required|date|after_or_equal:RAI_INSCRI_DATE_FIN',
        'RAI_RAID_DATE_FIN' => 'required|date|after_or_equal:RAI_RAID_DATE_DEBUT',
        'RAI_INSCRI_DATE_DEBUT' => 'required|date',
        'RAI_INSCRI_DATE_FIN' => 'required|date|after_or_equal:RAI_INSCRI_DATE_DEBUT',
        'RAI_CONTACT' => 'required|email|max:100',
        'RAI_TELEPHONE' => 'nullable|regex:/^[0-9\s\.\-\+\(\)]{10,20}$/|max:20',
        'RAI_IMAGE' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
    private $messages = [
        'RAI_NOM.required' => 'Le nom du raid est obligatoire.',
        'RAI_NOM.max' => 'La taille du nom doit être inférieure ou égale à 50 caractères. Veuillez entrer un nom plus court.',
        'RAI_LIEU.max' => 'La taille du lieu doit être inférieure ou égale à 100 caractères. Veuillez entrer un lieu plus court.',
        'RAI_LIEU.required' => 'Le lieu de départ est obligatoire.',
        'RAI_WEB.url' => "L'URL du site web n'est pas valide.",
        'RAI_CONTACT.email' => 'Veuillez entrer une adresse email valide (cette erreur ne devrait pas apparaître si vous avez sélectionné un responsable dans la liste).',
        'RAI_RAID_DATE_DEBUT.after_or_equal' => 'La date de début du raid doit être postérieure à la date de clôture des inscriptions.',
        'RAI_RAID_DATE_FIN.after_or_equal' => 'La date de fin du raid doit être postérieure ou égale à la date de début du raid.',
        'RAI_INSCRI_DATE_FIN.after_or_equal' => 'La date de fin des inscriptions doit être postérieure ou égale à la date de début des inscriptions.',
        'RAI_INSCRI_DATE_DEBUT.date' => 'La date de début des inscriptions doit être une date valide.',
        'RAI_INSCRI_DATE_FIN.date' => 'La date de fin des inscriptions doit être une date valide.',
        'RAI_RAID_DATE_DEBUT.required' => 'La date de début du raid est obligatoire.',
        'RAI_RAID_DATE_FIN.required' => 'La date de fin du raid est obligatoire.',
        'RAI_INSCRI_DATE_DEBUT.required' => 'La date de début des inscriptions est obligatoire.',
        'RAI_INSCRI_DATE_FIN.required' => 'La date de fin des inscriptions est obligatoire.',
        'RAI_IMAGE.image' => "Le fichier téléchargé doit être une image.",
        'RAI_IMAGE.mimes' => "L'image doit être au format jpeg, png, jpg ou gif.",
        'RAI_IMAGE.max' => "La taille de l'image ne doit pas dépasser 2 Mo.",
        'CLU_ID.exists' => "Le club sélectionné n'existe pas.",
        'UTI_ID.exists' => "Le responsable sélectionné n'existe pas.",
        'CLU_ID.required' => "Pas de club renseigné.",
        'UTI_ID.required' => "Pas de responsable renseigné.",
        'RAI_CONTACT.required' => "L'adresse e-mail du contact est obligatoire."
    ];

    public function index(Request $request) {
    $raids = Raid::getAllRaids();
    if ($request->wantsJson() || $request->query('format') === 'json') {
        return response()->json($raids);
    }
    return view('raids.raid', compact('raids'));
}

    public function create() /* affichage du formulaire */
    {
        $clubs = DB::table('vik_club')->orderBy('CLU_NOM')->pluck('CLU_NOM', 'CLU_ID');

        $responsables = DB::table('vik_utilisateur')
            ->select('UTI_ID', 'UTI_EMAIL', 'UTI_TELEPHONE', DB::raw("CONCAT(UTI_PRENOM, ' ', UTI_NOM) as name"))
            ->orderBy('UTI_NOM')
            ->get();


        return view('raids.raid-create', compact('clubs', 'responsables'));
    }

    public function store(Request $request) /* ajout d'un nouveau raid à la base de données */
    {
        $validated = $request->validate([
            'RAI_NOM' => 'required|max:50',
            'RAI_LIEU' => 'required|max:100',
            'RAI_WEB' => 'nullable',
            'CLU_ID' => 'required|exists:vik_club,CLU_ID',
            'UTI_ID' => 'required|exists:vik_utilisateur,UTI_ID',
            'RAI_RAID_DATE_DEBUT' => 'required|date',
            'RAI_RAID_DATE_FIN' => 'required|date|after_or_equal:RAI_RAID_DATE_DEBUT',
            'RAI_INSCRI_DATE_DEBUT' => 'required|date',
            'RAI_INSCRI_DATE_FIN' => 'required|date|after_or_equal:RAI_INSCRI_DATE_DEBUT',
            'RAI_CONTACT' => 'required|email|max:100',
            // RAI_IMAGE upload removed to avoid saving files into the repository
        ], [
            'RAI_NOM.required' => 'Le nom du raid est obligatoire.',
            'RAI_NOM.max' => 'La taille du nom doit être inférieure ou égale à 50 caractères. Veuillez entrer un nom plus court.',
            'RAI_LIEU.max' => 'La taille du lieu doit être inférieure ou égale à 100 caractères. Veuillez entrer un lieu plus court.',
            'RAI_LIEU.required' => 'Le lieu de départ est obligatoire.',
            'RAI_CONTACT.email' => 'Veuillez entrer une adresse email valide (cette erreur ne devrait pas apparaître si vous avez sélectionné un responsable dans la liste).',
            'RAI_RAID_DATE_FIN.after_or_equal' => 'La date de fin du raid doit être postérieure ou égale à la date de début du raid.',
            'RAI_INSCRI_DATE_FIN.after_or_equal' => 'La date de fin des inscriptions doit être postérieure ou égale à la date de début des inscriptions.',
            'RAI_INSCRI_DATE_DEBUT.date' => 'La date de début des inscriptions doit être une date valide.',
            'RAI_INSCRI_DATE_FIN.date' => 'La date de fin des inscriptions doit être une date valide.',
            'RAI_RAID_DATE_DEBUT.required' => 'La date de début du raid est obligatoire.',
            'RAI_RAID_DATE_FIN.required' => 'La date de fin du raid est obligatoire.',
            'RAI_INSCRI_DATE_DEBUT.required' => 'La date de début des inscriptions est obligatoire.',
            'RAI_INSCRI_DATE_FIN.required' => 'La date de fin des inscriptions est obligatoire.',
            // image validation/messages removed
            'CLU_ID.exists' => "Le club sélectionné n'existe pas.",
            'UTI_ID.exists' => "Le responsable sélectionné n'existe pas.",
            'CLU_ID.required' => "Pas de club renseigné.",
            'UTI_ID.required' => "Pas de responsable renseigné.",
            'RAI_CONTACT.required' => "L'adresse e-mail du contact est obligatoire."
        ]);

        // Validation personnalisée : la date de début du raid doit être après la date de fin des inscriptions
        $raidStart = \Carbon\Carbon::parse($validated['RAI_RAID_DATE_DEBUT']);
        $inscriptionEnd = \Carbon\Carbon::parse($validated['RAI_INSCRI_DATE_FIN']);
        
        if ($raidStart < $inscriptionEnd) {
            return back()->withErrors(['RAI_RAID_DATE_DEBUT' => 'La date de début du raid doit être postérieure à la date de clôture des inscriptions.'])->withInput();
        }

        // Récupère les coordonnées depuis vik_utilisateur
        $user = DB::table('vik_utilisateur')
            ->where('UTI_ID', $validated['UTI_ID'])
            ->select('UTI_EMAIL', 'UTI_TELEPHONE')
            ->first();

        if ($user) {
            $validated['RAI_CONTACT'] = $user->UTI_EMAIL;
            // Note: RAI_TELEPHONE n'existe pas dans la table vik_raid
        }

        // Image upload disabled: do not store uploaded image paths

        Raid::create($validated);

        // Ajouter le responsable à vik_responsable_club s'il n'y est pas déjà
        $responsableId = $validated['UTI_ID'];
        $clubId = $validated['CLU_ID'];
        $dejaResponsableClub = DB::table('vik_responsable_club')
            ->where('UTI_ID', $responsableId)
            ->exists();

        if (!$dejaResponsableClub) {
            // Récupérer les informations de l'utilisateur pour les insérer dans vik_responsable_club
            $userInfo = DB::table('vik_utilisateur')
                ->where('UTI_ID', $responsableId)
                ->first();

            if ($userInfo) {
                $userData = (array) $userInfo;
                unset($userData['created_at']);
                unset($userData['updated_at']);
                $userData['CLU_ID'] = $clubId; // Ajouter l'ID du club

                DB::table('vik_responsable_club')->updateOrInsert(
                    ['UTI_ID' => $responsableId],
                    $userData
                );
            }
        }

        // Ajouter le responsable à vik_responsable_raid s'il n'y est pas déjà
        $dejaResponsableRaid = DB::table('vik_responsable_raid')
            ->where('UTI_ID', $responsableId)
            ->exists();

        if (!$dejaResponsableRaid) {
            // Récupérer les informations de l'utilisateur pour les insérer dans vik_responsable_raid
            $userInfo = DB::table('vik_utilisateur')
                ->where('UTI_ID', $responsableId)
                ->first();

            if ($userInfo) {
                $userData = (array) $userInfo;
                unset($userData['created_at']);
                unset($userData['updated_at']);

                DB::table('vik_responsable_raid')->updateOrInsert(
                    ['UTI_ID' => $responsableId],
                    $userData
                );
            }
        }

        return redirect('/')->with('success', 'Raid créé avec succès !');
    }

    public function myRaids()
    {
        $raids = Raid::where('UTI_ID', auth()->id())->get();
        return view('raids.my-raids', compact('raids'));
    }

    public function edit($raid_id)
    {
        $raid = Raid::findOrFail($raid_id);
        
        // Vérifier que l'utilisateur est responsable de ce raid, admin, ou responsable du club
        $isOwner = ($raid->UTI_ID == auth()->id());
        $isAdmin = auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin();
        $isClubResponsable = DB::table('vik_responsable_club')
            ->where('UTI_ID', auth()->id())
            ->where('CLU_ID', $raid->CLU_ID)
            ->exists();

        if (!($isOwner || $isAdmin || $isClubResponsable)) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce raid.');
        }

        $clubs = DB::table('vik_club')->orderBy('CLU_NOM')->pluck('CLU_NOM', 'CLU_ID');

        $responsables = DB::table('vik_utilisateur')
            ->select('UTI_ID', 'UTI_EMAIL', 'UTI_TELEPHONE', DB::raw("CONCAT(UTI_PRENOM, ' ', UTI_NOM) as name"))
            ->orderBy('UTI_NOM')
            ->get();

        return view('raid-edit', compact('raid', 'clubs', 'responsables'));
    }

    public function update(Request $request, $raid_id)
    {
        $raid = Raid::findOrFail($raid_id);
        
        // Vérifier que l'utilisateur est responsable de ce raid, admin, ou responsable du club
        $isOwner = ($raid->UTI_ID == auth()->id());
        $isAdmin = auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin();
        $isClubResponsable = DB::table('vik_responsable_club')
            ->where('UTI_ID', auth()->id())
            ->where('CLU_ID', $raid->CLU_ID)
            ->exists();

        if (!($isOwner || $isAdmin || $isClubResponsable)) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce raid.');
        }

        // Sauvegarder l'ancien responsable
        $ancienResponsableId = $raid->UTI_ID;

        $validated = $request->validate([
            'RAI_NOM' => 'required|max:50',
            'RAI_LIEU' => 'required|max:100',
            'RAI_WEB' => 'nullable',
            'CLU_ID' => 'required|exists:vik_club,CLU_ID',
            'UTI_ID' => 'required|exists:vik_utilisateur,UTI_ID',
            'RAI_RAID_DATE_DEBUT' => 'required|date|after_or_equal:RAI_INSCRI_DATE_FIN',
            'RAI_RAID_DATE_FIN' => 'required|date|after_or_equal:RAI_RAID_DATE_DEBUT',
            'RAI_INSCRI_DATE_DEBUT' => 'required|date',
            'RAI_INSCRI_DATE_FIN' => 'required|date|after_or_equal:RAI_INSCRI_DATE_DEBUT',
            'RAI_CONTACT' => 'required|email|max:100',
            'RAI_TELEPHONE' => 'nullable|regex:/^[0-9\s\.\-\+\(\)]{10,20}$/|max:20',
        ], [
            'RAI_NOM.required' => 'Le nom du raid est obligatoire.',
            'RAI_NOM.max' => 'La taille du nom doit être inférieure ou égale à 50 caractères. Veuillez entrer un nom plus court.',
            'RAI_LIEU.max' => 'La taille du lieu doit être inférieure ou égale à 100 caractères. Veuillez entrer un lieu plus court.',
            'RAI_LIEU.required' => 'Le lieu de départ est obligatoire.',
            'RAI_CONTACT.email' => 'Veuillez entrer une adresse email valide (cette erreur ne devrait pas apparaître si vous avez sélectionné un responsable dans la liste).',
            'RAI_RAID_DATE_DEBUT.after_or_equal' => 'La date de début du raid doit être postérieure à la date de clôture des inscriptions.',
            'RAI_RAID_DATE_FIN.after_or_equal' => 'La date de fin du raid doit être postérieure à la date de début du raid.',
            'RAI_INSCRI_DATE_FIN.after_or_equal' => 'La date de clôture des inscriptions doit être postérieure à la date d\'ouverture des inscriptions.',
            'RAI_TELEPHONE.regex' => 'Le numéro de téléphone n\'est pas valide.',
            // image validation/messages removed
        ]);

        $user = DB::table('vik_utilisateur')
            ->where('UTI_ID', $validated['UTI_ID'])
            ->select('UTI_ID', 'UTI_EMAIL', 'UTI_TELEPHONE', 'UTI_NOM', 'UTI_PRENOM', 'UTI_NOM_UTILISATEUR', 'UTI_DATE_NAISSANCE', 'UTI_RUE', 'UTI_CODE_POSTAL', 'UTI_VILLE', 'UTI_LICENCE', 'UTI_MOT_DE_PASSE')
            ->first();

        if ($user) {
            $validated['RAI_CONTACT'] = $user->UTI_EMAIL;
            $validated['RAI_TELEPHONE'] = $user->UTI_TELEPHONE;

            DB::table('vik_responsable_raid')->insertOrIgnore([
                'UTI_ID' => $user->UTI_ID,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);
        }

        // Image upload disabled: do not store uploaded image paths

        $raid->update($validated);

        // When changing the responsable of a raid, only ensure the new responsable
        // is present in `vik_responsable_raid`. Do not modify `vik_responsable_club` here.
        $nouveauResponsableId = $validated['UTI_ID'];

        if ($ancienResponsableId != $nouveauResponsableId) {
            $nouveauDejaResponsableRaid = DB::table('vik_responsable_raid')
                ->where('UTI_ID', $nouveauResponsableId)
                ->exists();

            if (!$nouveauDejaResponsableRaid) {
                $userInfo = DB::table('vik_utilisateur')
                    ->where('UTI_ID', $nouveauResponsableId)
                    ->first();

                if ($userInfo) {
                    $userData = (array) $userInfo;
                    unset($userData['created_at']);
                    unset($userData['updated_at']);

                    DB::table('vik_responsable_raid')->updateOrInsert(
                        ['UTI_ID' => $nouveauResponsableId],
                        $userData
                    );
                }
            }
        }

        return redirect()->route('raids.index', $raid_id)->with('success', 'Raid modifié avec succès !');
    }
}
