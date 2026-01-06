<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('user.edit', ['user' => Auth::user()]);
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
            'UTI_ADRESSE' => 'nullable|string|max:50',
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