<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Gère la création de l'utilisateur.
     */
    public function register(Request $request)
    {
        $request->validate([
            'UTI_NOM' => 'required|string|max:255',
            'UTI_PRENOM' => 'required|string|max:255',
            'UTI_EMAIL' => 'required|string|email|max:255|unique:VIK_UTILISATEUR,UTI_EMAIL',
            'UTI_DATE_NAISSANCE' => 'required|date',
            'UTI_ADRESSE' => 'nullable|string|max:500',
            'UTI_TELEPHONE' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'UTI_NOM' => $request->UTI_NOM,
            'UTI_PRENOM' => $request->UTI_PRENOM,
            'UTI_EMAIL' => $request->UTI_EMAIL,
            'UTI_DATE_NAISSANCE' => $request->UTI_DATE_NAISSANCE,
            'UTI_ADRESSE' => $request->UTI_ADRESSE,
            'UTI_TELEPHONE' => $request->UTI_TELEPHONE,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès. Veuillez vous connecter.');
    }
}