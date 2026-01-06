<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Coureur;
use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegistrationForm()
    {
        $clubs = Club::query()->get();
        return view('auth.register', compact('clubs'));
    }

    /**
     * Gère la création de l'utilisateur.
     */
    public function register(Request $request)
    {
        $request->validate([
            'UTI_NOM' => 'required|string|max:50',
            'UTI_PRENOM' => 'required|string|max:50',
            'UTI_EMAIL' => 'required|string|email|max:100',
            'UTI_NOM_UTILISATEUR' => 'required|string|max:50|unique:VIK_UTILISATEUR,UTI_NOM_UTILISATEUR',
            'UTI_DATE_NAISSANCE' => 'required|date',
            'UTI_RUE' => 'required|string|max:100',
            'UTI_CODE_POSTAL' => 'required|string|max:10',
            'UTI_VILLE' => 'required|string|max:50',
            'UTI_TELEPHONE' => 'nullable|string|max:16',
            'UTI_MOT_DE_PASSE' => 'required|string|min:8',
            'UTI_LICENCE' => 'nullable|string|max:15',
            'CLU_ID' => 'nullable|exists:vik_club,CLU_ID',
        ], [
            'UTI_NOM.max' => "La taille maximal du nom est de 50 caractère",
            'UTI_PRENOM.max' => "La taille maximal du prénom est de 50 caractère",
            'UTI_EMAIL.max' => "La taille maximal du mail est de 100 caractère",
            'UTI_NOM_UTILISATEUR.unique' => "Ce nom d'utilisateur est déjà pris.",
            'UTI_NOM_UTILISATEUR.max' => "La taille maximal du nom d'utilisateur est de 50 caractère",
            'UTI_RUE.max' => "La taille maximal de la rue est de 100 caractère",
            'UTI_CODE_POSTAL.max' => "Mauvais format de code postal",
            'UTI_VILLE.max' => "La taille maximal de la ville est de 50 caractère",
            'UTI_TELEPHONE.max' => "La taille maximal du téléphone est de 16 caractère",
            'UTI_MOT_DE_PASSE.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'UTI_LICENCE.max' => "La taille maximal de la licence est de 15 caractère",
            'CLU_ID.exists' => "Ce club n'existe pas.",
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'UTI_NOM' => $request->UTI_NOM,
                'UTI_PRENOM' => $request->UTI_PRENOM,
                'UTI_EMAIL' => $request->UTI_EMAIL,
                'UTI_NOM_UTILISATEUR' => $request->UTI_NOM_UTILISATEUR,
                'UTI_DATE_NAISSANCE' => $request->UTI_DATE_NAISSANCE,
                'UTI_RUE' => $request->UTI_RUE,
                'UTI_CODE_POSTAL' => $request->UTI_CODE_POSTAL,
                'UTI_VILLE' => $request->UTI_VILLE,
                'UTI_TELEPHONE' => $request->UTI_TELEPHONE,
                'UTI_MOT_DE_PASSE' => Hash::make($request->UTI_MOT_DE_PASSE),
                'UTI_LICENCE' => $request->UTI_LICENCE,
            ]);

            Coureur::create([
                'UTI_ID' => $user->UTI_ID,
                'UTI_NOM' => $user->UTI_NOM,
                'UTI_PRENOM' => $user->UTI_PRENOM,
                'UTI_EMAIL' => $user->UTI_EMAIL,
                'UTI_DATE_NAISSANCE' => $user->UTI_DATE_NAISSANCE,
                'CLU_ID' => $request->CLU_ID,
                'UTI_LICENCE' => $user->UTI_LICENCE,
                'UTI_NOM_UTILISATEUR' => $user->UTI_NOM_UTILISATEUR,
                'UTI_RUE' => $user->UTI_RUE,
                'UTI_CODE_POSTAL' => $user->UTI_CODE_POSTAL,
                'UTI_VILLE' => $user->UTI_VILLE,
                'UTI_TELEPHONE' => $user->UTI_TELEPHONE,
                'UTI_MOT_DE_PASSE' => $user->UTI_MOT_DE_PASSE,
            ]);
        });


        return redirect()->route('login')->with('success', 'Inscription réussie !');
    }
}
