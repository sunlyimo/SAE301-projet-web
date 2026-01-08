<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        // Si l'utilisateur est connecté, utiliser ses informations
        if (auth()->check()) {
            $name = auth()->user()->name;
            $email = auth()->user()->email;

            $validated = $request->validate([
                'message' => 'required|string|max:5000',
            ]);
        } else {
            // Si non connecté, valider les champs name et email
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string|max:5000',
            ]);

            $name = $validated['name'];
            $email = $validated['email'];
        }

        // Log le message de contact de manière détaillée
        Log::info('=== MESSAGE DE CONTACT REÇU ===');
        Log::info('Destinataire: association@cartorun.fr');
        Log::info('De: ' . $name . ' <' . $email . '>');
        Log::info('Sujet: Contact depuis CartoRun - ' . $name);
        Log::info('Message:');
        Log::info($validated['message']);
        Log::info('Date: ' . now()->format('d/m/Y H:i:s'));
        Log::info('===================================');

        return redirect()->route('contact.show')->with('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    }
}
