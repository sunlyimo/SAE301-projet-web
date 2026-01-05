<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller {
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $user = User::create([
            'UTI_NOM' => $request->UTI_NOM,
            'UTI_PRENOM' => $request->UTI_PRENOM,
            'UTI_EMAIL' => $request->UTI_EMAIL,
            'UTI_DATE_NAISSANCE' => $request->UTI_DATE_NAISSANCE,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect('/');
    }
}