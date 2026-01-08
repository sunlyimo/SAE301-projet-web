<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raid;
use Illuminate\Support\Facades\DB;

class RaidController extends Controller
{
    public function index()
    {
        return view('raids.raid')
            ->with('raids', Raid::getFuturRaid());
    }

    public function create() /* affichage du formulaire */
    {
        $clubs = DB::table('VIK_CLUB')->orderBy('CLU_NOM')->pluck('CLU_NOM', 'CLU_ID');

        $responsables = DB::table('VIK_UTILISATEUR')
            ->select('UTI_ID', 'UTI_EMAIL', 'UTI_TELEPHONE', DB::raw("CONCAT(UTI_PRENOM, ' ', UTI_NOM) as name"))
            ->orderBy('UTI_NOM')
            ->get();


        return view('raid-create', compact('clubs', 'responsables'));
    }

    public function store(Request $request) /* ajout d'un nouveau raid à la base de données */
    {
        $validated = $request->validate([
            'RAI_NOM' => 'required|max:50',
            'RAI_LIEU' => 'required|max:100',
            'RAI_WEB' => 'nullable|url',
            'CLU_ID' => 'required|exists:VIK_CLUB,CLU_ID',
            'UTI_ID' => 'required|exists:VIK_UTILISATEUR,UTI_ID',
            'RAI_RAID_DATE_DEBUT' => 'required|date|after_or_equal:RAI_INSCRI_DATE_FIN',
            'RAI_RAID_DATE_FIN' => 'required|date|after_or_equal:RAI_RAID_DATE_DEBUT',
            'RAI_INSCRI_DATE_DEBUT' => 'required|date',
            'RAI_INSCRI_DATE_FIN' => 'required|date|after_or_equal:RAI_INSCRI_DATE_DEBUT',
            'RAI_CONTACT' => 'required|email|max:100',
            'RAI_TELEPHONE' => 'nullable|regex:/^[0-9\s\.\-\+\(\)]{10,20}$/|max:20',
            'RAI_IMAGE' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
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
        ]);

        echo '<script>alert("Validated Data:", ' . json_encode($validated) . ');</script>';

        $user = DB::table('VIK_UTILISATEUR') /* récup l'utilisateur */
            ->where('UTI_ID', $validated['UTI_ID'])
            ->select('UTI_ID', 'UTI_EMAIL', 'UTI_TELEPHONE', 'UTI_NOM', 'UTI_PRENOM', 'UTI_NOM_UTILISATEUR', 'UTI_DATE_NAISSANCE', 'UTI_RUE', 'UTI_CODE_POSTAL', 'UTI_VILLE', 'UTI_LICENCE', 'UTI_MOT_DE_PASSE')
            ->first();

        if ($user) {
            $validated['RAI_CONTACT'] = $user->UTI_EMAIL;
            $validated['RAI_TELEPHONE'] = $user->UTI_TELEPHONE;

            DB::table('VIK_RESPONSABLE_RAID')->insertOrIgnore([
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

        if ($request->hasFile('RAI_IMAGE')) {
            $path = $request->file('RAI_IMAGE')->store('raids', 'public');
            $validated['RAI_IMAGE'] = $path;
        }

        Raid::create($validated);

        return redirect('/')->with('success', 'Raid créé avec succès !');
    }
}
