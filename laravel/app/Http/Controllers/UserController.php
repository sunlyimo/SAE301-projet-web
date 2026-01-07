<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile', ['user' => Auth::user(), 'clubs' => Club::all()]);
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'UTI_NOM' => 'string|max:50',
            'UTI_PRENOM' => 'string|max:50',
            'UTI_NOM_UTILISATEUR' => 'string|max:255',
            'UTI_EMAIL' => 'email|max:150',
            'UTI_TELEPHONE' => 'nullable|string|max:16',
            'UTI_RUE' => 'nullable|string|max:50',
            'UTI_CODE_POSTAL' => 'nullable|string|max:10',
            'UTI_VILLE' => 'nullable|string|max:50',
        ]);

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

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
