<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResponsableInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $club;
    public $token;
    public $user;
    public $isNewUser = false;

    /**
     * Create a new message instance.
     */
    public function __construct($club, $token, $user, $isNewUser = false)
    {
        $this->club = $club;
        $this->token = $token;
        $this->user = $user;
        $this->isNewUser = $isNewUser;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Invitation à devenir responsable du club {$this->club->CLU_NOM}")
                    ->view('emails.responsable-invitation')
                    ->with([
                        'club' => $this->club,
                        'token' => $this->token,
                        'user' => $this->user,
                        'isNewUser' => $this->isNewUser,
                    ]);
    }
}
