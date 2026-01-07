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

        // If an intent was stored (e.g., to show an invitation), redirect there and forget it
        $intent = session()->pull('post_login_intent', null);
        if ($intent) {
            return redirect($intent);
        }

        session()->flash('welcome', Auth::user()->UTI_NOM_UTILISATEUR);

        return redirect()->intended('/');
    }

        return back()->withErrors(['UTI_NOM_UTILISATEUR' => 'Identifiants invalides.'])->withInput();
    }

    public function logout() {
        Auth::logout();
        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }
}