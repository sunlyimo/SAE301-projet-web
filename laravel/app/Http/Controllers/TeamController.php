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
                        ->with(['chef'])
                        ->firstOrFail();

        // Charger les membres en excluant le chef d'équipe
        $equipe->load(['membres' => function($query) use ($rai_id, $cou_id, $equipe) {
            $query->where('RAI_ID', $rai_id)
                  ->where('COU_ID', $cou_id)
                  ->where('UTI_ID', '!=', $equipe->UTI_ID) // Exclure le chef
                  ->with('utilisateur');
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

        // Calculer le nombre de participants (membres non-chef + chef si il participe)
        $nbMembres = Appartient::where('RAI_ID', $rai_id)
                               ->where('COU_ID', $cou_id)
                               ->where('EQU_ID', $equ_id)
                               ->where('UTI_ID', '!=', $equipe->UTI_ID)
                               ->count();

        $nbParticipants = $nbMembres + ($chefParticipe ? 1 : 0);

        return view('teams.show', compact('equipe', 'isChef', 'chefParticipe', 'course', 'nbParticipants'));
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

        $course = \App\Models\Course::where('RAI_ID', $rai_id)->where('COU_ID', $cou_id)->first();

        // Vérifier le nombre de participants (membres + chef si il participe)
        $chefParticipe = Appartient::where('RAI_ID', $rai_id)
                                   ->where('COU_ID', $cou_id)
                                   ->where('EQU_ID', $equ_id)
                                   ->where('UTI_ID', $equipe->UTI_ID)
                                   ->exists();

        $nbParticipants = $equipe->membres()->count() + ($chefParticipe ? 1 : 0);

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
            $nbParticipants = $equipe->membres()->count();

            if ($nbParticipants >= $course->COU_PARTICIPANT_PAR_EQUIPE_MAX) {
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
}
