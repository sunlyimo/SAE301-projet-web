<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Log le message de contact
        Log::info('Message de contact reçu', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ]);

        // Optionnel: Envoyer un email (nécessite la configuration SMTP)
        // Mail::raw($validated['message'], function ($message) use ($validated) {
        //     $message->to('association@cartorun.fr')
        //         ->subject('Contact depuis CartoRun - ' . $validated['name'])
        //         ->replyTo($validated['email']);
        // });

        return redirect()->route('contact.show')->with('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    }
}
