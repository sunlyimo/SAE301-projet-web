<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller {
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'UTI_NOM' => 'required|string|max:255',
            'UTI_PRENOM' => 'required|string|max:255',
            'UTI_EMAIL' => 'required|string|email|max:255|unique:VIK_UTILISATEUR,UTI_EMAIL',
            'UTI_DATE_NAISSANCE' => 'required|date',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'UTI_NOM' => $request->UTI_NOM,
            'UTI_PRENOM' => $request->UTI_PRENOM,
            'UTI_EMAIL' => $request->UTI_EMAIL,
            'UTI_DATE_NAISSANCE' => $request->UTI_DATE_NAISSANCE,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Votre compte a été créé ! Connectez-vous.');
    }
}