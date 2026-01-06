<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
    $request->validate([
        'UTI_NOM_UTILISATEUR' => 'required|string',
        'UTI_MOT_DE_PASSE' => 'required|string',
    ]);

    $credentials = [
        'UTI_NOM_UTILISATEUR' => $request->UTI_NOM_UTILISATEUR,
        'password' => $request->UTI_MOT_DE_PASSE,
    ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

        return back()->withErrors(['UTI_NOM_UTILISATEUR' => 'Identifiants invalides.'])->withInput();
    }

    public function logout() {
        Auth::logout();
        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }
}