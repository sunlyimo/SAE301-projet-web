<?php

namespace App\Http\Controllers;

use App\Models\Club;
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
        $clubs = Club::all();
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

        $allTeams = $equipesChef->concat($equipesMembre)->unique(function ($item) {
            return $item->RAI_ID . '-' . $item->COU_ID . '-' . $item->EQU_ID;
        });

        $allTeams = $allTeams->sortByDesc(function($team) {
            return $team->course->COU_DATE_DEBUT ?? 0;
        });

        $uniqueCourses = $allTeams->map(fn($t) => $t->course)->unique(function ($course) {
            return $course ? ($course->RAI_ID . '-' . $course->COU_ID) : null;
        })->filter();


        return view('user.profile', compact('user', 'allTeams', 'uniqueCourses', 'clubs'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $removeLicence = $request->boolean('remove_licence');

        $rules = [
            'UTI_NOM' => 'string|max:50',
            'UTI_PRENOM' => 'string|max:50',
            'UTI_NOM_UTILISATEUR' => 'string|max:50|unique:vik_utilisateur,UTI_NOM_UTILISATEUR,' . $user->UTI_ID . ',UTI_ID',
            'UTI_EMAIL' => 'email|max:100',
            'UTI_TELEPHONE' => 'nullable|string|max:10',
            'UTI_RUE' => 'nullable|string|max:100',
            'UTI_CODE_POSTAL' => 'nullable|string|max:6',
            'UTI_VILLE' => 'nullable|string|max:50',
        ];

        if (!$removeLicence && ($request->filled('UTI_LICENCE') || $request->has('UTI_CLUB'))) {
            $rules['UTI_LICENCE'] = 'required|string|max:15';
            $rules['CLU_ID'] = 'required|exists:vik_club,CLU_ID';
        }

        $data = $request->validate($rules, [
            'UTI_NOM.max' => "La taille maximal du nom est de 50 caractère",
            'UTI_PRENOM.max' => "La taille maximal du prénom est de 50 caractère",
            'UTI_EMAIL.max' => "La taille maximal du mail est de 100 caractère",
            'UTI_NOM_UTILISATEUR.unique' => "Ce nom d'utilisateur est déjà pris.",
            'UTI_NOM_UTILISATEUR.max' => "La taille maximal du nom d'utilisateur est de 50 caractère",
            'UTI_RUE.max' => "La taille maximal de la rue est de 100 caractère",
            'UTI_CODE_POSTAL.max' => "Mauvais format de code postal",
            'UTI_VILLE.max' => "La taille maximal de la ville est de 50 caractère",
            'UTI_TELEPHONE.max' => "La taille maximal du téléphone est de 10 caractère",
            'UTI_LICENCE.max' => "La taille maximal de la licence est de 15 caractère",
            'CLU_ID.exists' => "Ce club n'existe pas.",
            'UTI_LICENCE.required' => 'La licence est obligatoire.',
            'CLU_ID.required' => 'Le club est obligatoire.',
        ]);

        if ($removeLicence) {
            $data['UTI_LICENCE'] = null;
            $data['CLU_ID'] = null;
        }

        if ($request->filled('new_password')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required',
            ], [
                'current_password.required' => 'Le champ est obligatoire',
                'new_password.required' => 'Le champ est obligatoire',
                'new_password_confirmation.required' => 'Le champ est obligatoire',
                'current_password.current_password' => 'Mot de passe actuel incorrect',
                'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractures',
                'new_password.confirmed' => 'Le nouveau mot de passe ne coincide pas avec la confirmation du mot de passe',

            ]);
            $data['UTI_MOT_DE_PASSE'] = Hash::make($request->new_password);
        }

        $user->update($data);

        if ($user->coureur) {
            $user->coureur->update([
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'CLU_ID' => $removeLicence ? null : $data['CLU_ID'] ?? $user->coureur->CLU_ID,
            ]);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
