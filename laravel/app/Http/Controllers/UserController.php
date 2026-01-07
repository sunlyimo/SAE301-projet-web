<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Equipe;
use App\Models\Appartient;

class UserController extends Controller
{
public function show()
    {
        $user = Auth::user();
        $userId = $user->UTI_ID;
        $equipesChef = Equipe::where('UTI_ID', $userId)->get();
        $appartenances = Appartient::where('UTI_ID', $userId)->get();
        $equipesMembre = collect();
        foreach ($appartenances as $app) {
            $team = Equipe::where('RAI_ID', $app->RAI_ID)
                ->where('COU_ID', $app->COU_ID)
                ->where('EQU_ID', $app->EQU_ID)
                ->first();
            
            if ($team) {
                $equipesMembre->push($team);
            }
        }
        $allTeams = $equipesChef->concat($equipesMembre);
        $allTeams = $allTeams->sortByDesc(function($team) {
            return $team->course->COU_DATE_DEBUT ?? 0;
        });

        $uniqueCourses = $allTeams->map(fn($t) => $t->course)->unique(function ($course) {
            return $course ? ($course->RAI_ID . '-' . $course->COU_ID) : null;
        })->filter();
        return view('user.profile', compact('user', 'allTeams', 'uniqueCourses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'UTI_NOM' => 'required|string|max:50',
            'UTI_PRENOM' => 'required|string|max:50',
            'UTI_NOM_UTILISATEUR' => [
                'required', 'string', 'max:255', 
                Rule::unique('vik_utilisateur', 'UTI_NOM_UTILISATEUR')->ignore($user->UTI_ID, 'UTI_ID')
            ],
            'UTI_EMAIL' => [
                'required', 'email', 'max:150',
                Rule::unique('vik_utilisateur', 'UTI_EMAIL')->ignore($user->UTI_ID, 'UTI_ID')
            ],
            'UTI_TELEPHONE' => 'nullable|string|max:16',
            'UTI_RUE' => 'nullable|string|max:50',    
            'UTI_CODE_POSTAL' => 'nullable|string|max:10',
            'UTI_VILLE' => 'nullable|string|max:50',
        ]);

        if ($request->filled('new_password')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|min:8',
            ]);
            $data['UTI_MOT_DE_PASSE'] = Hash::make($request->new_password);
        }

        $user->update($data);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}