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
                        ->with(['chef', 'membres.utilisateur'])
                        ->firstOrFail();

        $isChef = $equipe->UTI_ID == Auth::id();
        $isMembre = $equipe->membres->contains('UTI_ID', Auth::id());

        if (!$isChef && !$isMembre) {
            abort(403, "Accès refusé.");
        }

        return view('teams.show', compact('equipe', 'isChef'));
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
        $nbMembres = 1 + $equipe->membres()->count();
        
        if ($nbMembres >= $course->COU_PARTICIPANT_PAR_EQUIPE_MAX) {
            return back()->withErrors(['pseudo' => "L'équipe est complète !"]);
        }

        $userToAdd = User::where('UTI_NOM_UTILISATEUR', $request->pseudo)->first();

        if ($userToAdd->UTI_ID == $equipe->UTI_ID) {
            return back()->withErrors(['pseudo' => 'Vous êtes déjà le chef de cette équipe.']);
        }

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
}