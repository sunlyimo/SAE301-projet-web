<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipe;
use App\Models\Appartient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function show($rai_id, $cou_id, $equ_id)
    {
        $equipe = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->where('EQU_ID', $equ_id)
                        ->with(['chef.coureur.rpps' => function($query) use ($rai_id, $cou_id) {
                            $query->where('RAI_ID', $rai_id)->where('COU_ID', $cou_id);
                        }])
                        ->firstOrFail();

        // Charger les membres en excluant le chef d'équipe
        $equipe->load(['membres' => function($query) use ($rai_id, $cou_id, $equipe) {
            $query->where('RAI_ID', $rai_id)
                  ->where('COU_ID', $cou_id)
                  ->where('UTI_ID', '!=', $equipe->UTI_ID) // Exclure le chef
                  ->with(['utilisateur.coureur.rpps' => function($q) use ($rai_id, $cou_id) {
                      $q->where('RAI_ID', $rai_id)->where('COU_ID', $cou_id);
                  }]);
        }]);

        $isChef = $equipe->UTI_ID == Auth::id();
        $isMembre = Appartient::where('RAI_ID', $rai_id)
                              ->where('COU_ID', $cou_id)
                              ->where('EQU_ID', $equ_id)
                              ->where('UTI_ID', Auth::id())
                              ->exists();

        if (!$isChef && !$isMembre) {
            abort(403, "Accès refusé.");
        }

        // Vérifier si le chef participe à la course
        $chefParticipe = Appartient::where('RAI_ID', $rai_id)
                                   ->where('COU_ID', $cou_id)
                                   ->where('EQU_ID', $equ_id)
                                   ->where('UTI_ID', $equipe->UTI_ID)
                                   ->exists();

        // Récupérer les informations de la course
        $course = \App\Models\Course::where('RAI_ID', $rai_id)
                                     ->where('COU_ID', $cou_id)
                                     ->first();

        // Récupérer le Raid pour les dates d'inscription
        $raid = \App\Models\Raid::findOrFail($rai_id);
        $now = now();

        // Vérifier si la période d'inscription est active
        $inscriptionsOuvertes = $now->gte($raid->RAI_INSCRI_DATE_DEBUT) && $now->lte($raid->RAI_INSCRI_DATE_FIN);

        // Calculer le nombre de participants (membres non-chef + chef si il participe)
        $nbMembres = Appartient::where('RAI_ID', $rai_id)
                               ->where('COU_ID', $cou_id)
                               ->where('EQU_ID', $equ_id)
                               ->where('UTI_ID', '!=', $equipe->UTI_ID)
                               ->count();

        $nbParticipants = $nbMembres + ($chefParticipe ? 1 : 0);

        return view('teams.show', compact('equipe', 'isChef', 'chefParticipe', 'course', 'nbParticipants', 'inscriptionsOuvertes', 'raid'));
    }

    public function addMember(Request $request, $rai_id, $cou_id, $equ_id)
    {
        $request->validate([
            'pseudo' => 'required|string|exists:vik_utilisateur,UTI_NOM_UTILISATEUR'
        ], [
            'pseudo.exists' => "Ce nom d'utilisateur n'existe pas."
        ]);

        $equipe = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->where('EQU_ID', $equ_id)
                        ->firstOrFail();

        if ($equipe->UTI_ID != Auth::id()) {
            abort(403, "Seul le chef d'équipe peut ajouter des membres.");
        }

        // Récupérer le Raid pour vérifier les dates d'inscription
        $raid = \App\Models\Raid::findOrFail($rai_id);
        $now = now();

        // Vérifier si la période d'inscription est active
        if ($now->lt($raid->RAI_INSCRI_DATE_DEBUT)) {
            return back()->withErrors(['pseudo' => "Les inscriptions n'ont pas encore commencé."]);
        }

        if ($now->gt($raid->RAI_INSCRI_DATE_FIN)) {
            return back()->withErrors(['pseudo' => "Les inscriptions sont terminées. Impossible d'ajouter des participants."]);
        }

        $course = \App\Models\Course::where('RAI_ID', $rai_id)->where('COU_ID', $cou_id)->first();

        // Vérifier le nombre de participants (membres + chef si il participe)
        $chefParticipe = Appartient::where('RAI_ID', $rai_id)
                                   ->where('COU_ID', $cou_id)
                                   ->where('EQU_ID', $equ_id)
                                   ->where('UTI_ID', $equipe->UTI_ID)
                                   ->exists();

        // Compter les membres (excluant le chef)
        $nbMembres = Appartient::where('RAI_ID', $rai_id)
                               ->where('COU_ID', $cou_id)
                               ->where('EQU_ID', $equ_id)
                               ->where('UTI_ID', '!=', $equipe->UTI_ID)
                               ->count();

        $nbParticipants = $nbMembres + ($chefParticipe ? 1 : 0);

        if ($nbParticipants >= $course->COU_PARTICIPANT_PAR_EQUIPE_MAX) {
            return back()->withErrors(['pseudo' => "L'équipe est complète !"]);
        }

        $userToAdd = User::where('UTI_NOM_UTILISATEUR', $request->pseudo)->first();

        $exists = Appartient::where('RAI_ID', $rai_id)
                            ->where('COU_ID', $cou_id)
                            ->where('EQU_ID', $equ_id)
                            ->where('UTI_ID', $userToAdd->UTI_ID)
                            ->exists();

        if ($exists) {
            return back()->withErrors(['pseudo' => "Cet utilisateur fait déjà partie de l'équipe."]);
        }

        Appartient::create([
            'UTI_ID' => $userToAdd->UTI_ID,
            'RAI_ID' => $rai_id,
            'COU_ID' => $cou_id,
            'EQU_ID' => $equ_id
        ]);

        return back()->with('success', "{$userToAdd->UTI_PRENOM} a été ajouté à l'équipe !");
    }

    public function toggleChefParticipation($rai_id, $cou_id, $equ_id)
    {
        $equipe = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->where('EQU_ID', $equ_id)
                        ->firstOrFail();

        if ($equipe->UTI_ID != Auth::id()) {
            abort(403, "Seul le chef d'équipe peut modifier sa participation.");
        }

        // Récupérer le Raid pour vérifier les dates d'inscription
        $raid = \App\Models\Raid::findOrFail($rai_id);
        $now = now();

        // Vérifier si la période d'inscription est active
        if ($now->lt($raid->RAI_INSCRI_DATE_DEBUT)) {
            return back()->withErrors(['chef' => "Les inscriptions n'ont pas encore commencé."]);
        }

        if ($now->gt($raid->RAI_INSCRI_DATE_FIN)) {
            return back()->withErrors(['chef' => "Les inscriptions sont terminées. Impossible de modifier votre participation."]);
        }

        $course = \App\Models\Course::where('RAI_ID', $rai_id)->where('COU_ID', $cou_id)->first();

        $chefParticipe = Appartient::where('RAI_ID', $rai_id)
                                   ->where('COU_ID', $cou_id)
                                   ->where('EQU_ID', $equ_id)
                                   ->where('UTI_ID', $equipe->UTI_ID)
                                   ->exists();

        if ($chefParticipe) {
            // Le chef participe déjà, on le retire
            Appartient::where('RAI_ID', $rai_id)
                      ->where('COU_ID', $cou_id)
                      ->where('EQU_ID', $equ_id)
                      ->where('UTI_ID', $equipe->UTI_ID)
                      ->delete();
            return back()->with('success', "Vous ne participez plus à la course.");
        } else {
            // Le chef ne participe pas, on l'ajoute
            // Compter les membres (excluant le chef)
            $nbMembres = Appartient::where('RAI_ID', $rai_id)
                                   ->where('COU_ID', $cou_id)
                                   ->where('EQU_ID', $equ_id)
                                   ->where('UTI_ID', '!=', $equipe->UTI_ID)
                                   ->count();

            // Le chef va être ajouté, donc on vérifie si nbMembres + 1 dépasse le max
            if (($nbMembres + 1) > $course->COU_PARTICIPANT_PAR_EQUIPE_MAX) {
                return back()->withErrors(['chef' => "L'équipe est complète ! Impossible d'ajouter le chef comme participant."]);
            }

            Appartient::create([
                'UTI_ID' => $equipe->UTI_ID,
                'RAI_ID' => $rai_id,
                'COU_ID' => $cou_id,
                'EQU_ID' => $equ_id
            ]);

            return back()->with('success', "Vous participez maintenant à la course !");
        }
    }

    public function removeMember($rai_id, $cou_id, $equ_id, $uti_id)
    {
        $equipe = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->where('EQU_ID', $equ_id)
                        ->firstOrFail();

        // Vérifier que l'utilisateur connecté est le chef de l'équipe
        if ($equipe->UTI_ID != Auth::id()) {
            abort(403, "Seul le chef d'équipe peut retirer des membres.");
        }

        // Récupérer le Raid pour vérifier les dates d'inscription
        $raid = \App\Models\Raid::findOrFail($rai_id);
        $now = now();

        // Vérifier si la période d'inscription est active
        if ($now->lt($raid->RAI_INSCRI_DATE_DEBUT)) {
            return back()->withErrors(['remove' => "Les inscriptions n'ont pas encore commencé."]);
        }

        if ($now->gt($raid->RAI_INSCRI_DATE_FIN)) {
            return back()->withErrors(['remove' => "Les inscriptions sont terminées. Impossible de modifier l'équipe."]);
        }

        // Vérifier que l'utilisateur à retirer n'est pas le chef
        if ($uti_id == $equipe->UTI_ID) {
            return back()->withErrors(['remove' => "Le chef d'équipe ne peut pas être retiré. Utilisez la case à cocher pour gérer votre participation."]);
        }

        // Vérifier que l'utilisateur est bien membre de l'équipe
        $exists = Appartient::where('RAI_ID', $rai_id)
                            ->where('COU_ID', $cou_id)
                            ->where('EQU_ID', $equ_id)
                            ->where('UTI_ID', $uti_id)
                            ->exists();

        if (!$exists) {
            return back()->withErrors(['remove' => "Cet utilisateur ne fait pas partie de l'équipe."]);
        }

        $user = User::find($uti_id);

        // Supprimer avec la méthode delete directement sur le query builder
        Appartient::where('RAI_ID', $rai_id)
                  ->where('COU_ID', $cou_id)
                  ->where('EQU_ID', $equ_id)
                  ->where('UTI_ID', $uti_id)
                  ->delete();

        return back()->with('success', "{$user->UTI_PRENOM} {$user->UTI_NOM} a été retiré de l'équipe.");
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('UTI_NOM_UTILISATEUR', 'LIKE', "%{$query}%")
                    ->orWhere('UTI_PRENOM', 'LIKE', "%{$query}%")
                    ->orWhere('UTI_NOM', 'LIKE', "%{$query}%")
                    ->limit(10)
                    ->get(['UTI_ID', 'UTI_NOM_UTILISATEUR', 'UTI_PRENOM', 'UTI_NOM']);

        return response()->json($users);
    }

    public function updateRpps(Request $request, $rai_id, $cou_id, $equ_id, $uti_id)
    {
        $request->validate([
            'rpps' => 'nullable|string|size:11|regex:/^[0-9]{11}$/'
        ], [
            'rpps.size' => 'Le numéro RPPS doit contenir exactement 11 chiffres.',
            'rpps.regex' => 'Le numéro RPPS doit contenir uniquement des chiffres.'
        ]);

        $equipe = Equipe::where('RAI_ID', $rai_id)
                        ->where('COU_ID', $cou_id)
                        ->where('EQU_ID', $equ_id)
                        ->firstOrFail();

        // Vérifier que l'utilisateur connecté est le chef de l'équipe
        if ($equipe->UTI_ID != Auth::id()) {
            abort(403, "Seul le chef d'équipe peut modifier les informations des participants.");
        }

        // Récupérer le Raid pour vérifier les dates d'inscription
        $raid = \App\Models\Raid::findOrFail($rai_id);
        $now = now();

        // Vérifier si la période d'inscription est active
        if ($now->lt($raid->RAI_INSCRI_DATE_DEBUT)) {
            return back()->withErrors(['rpps' => "Les inscriptions n'ont pas encore commencé."]);
        }

        if ($now->gt($raid->RAI_INSCRI_DATE_FIN)) {
            return back()->withErrors(['rpps' => "Les inscriptions sont terminées."]);
        }

        // Vérifier que l'utilisateur fait partie de l'équipe
        $isMembre = Appartient::where('RAI_ID', $rai_id)
                              ->where('COU_ID', $cou_id)
                              ->where('EQU_ID', $equ_id)
                              ->where('UTI_ID', $uti_id)
                              ->exists();

        $isChef = ($equipe->UTI_ID == $uti_id);

        if (!$isMembre && !$isChef) {
            return back()->withErrors(['rpps' => "Cet utilisateur ne fait pas partie de l'équipe."]);
        }

        // Récupérer l'utilisateur
        $user = User::findOrFail($uti_id);

        // Vérifier que l'utilisateur n'a pas de licence
        if ($user->UTI_LICENCE) {
            return back()->withErrors(['rpps' => "Impossible de définir un RPPS pour un utilisateur ayant déjà une licence."]);
        }

        // Créer ou mettre à jour le coureur si nécessaire
        $coureur = \App\Models\Coureur::firstOrCreate(
            ['UTI_ID' => $uti_id],
            [
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]
        );

        // Mettre à jour ou créer le RPPS spécifique à cette course
        if ($request->rpps) {
            \App\Models\CoureurRpps::updateOrCreate(
                [
                    'UTI_ID' => $uti_id,
                    'RAI_ID' => $rai_id,
                    'COU_ID' => $cou_id
                ],
                [
                    'CRP_NUMERO_RPPS' => $request->rpps
                ]
            );
        } else {
            // Si le RPPS est vide, on supprime l'enregistrement
            \App\Models\CoureurRpps::where('UTI_ID', $uti_id)
                                   ->where('RAI_ID', $rai_id)
                                   ->where('COU_ID', $cou_id)
                                   ->delete();
        }

        return back()->with('success', "Le numéro Pass'compétition a été enregistré avec succès !");
    }
}
