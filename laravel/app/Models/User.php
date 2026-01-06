<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'VIK_UTILISATEUR';
    protected $primaryKey = 'UTI_ID';

    /**
     * Les attributs qui peuvent être remplis
     * On utilise ici les noms exacts du schéma
     */
    protected $fillable = [
        'UTI_NOM',
        'UTI_PRENOM',
        'UTI_EMAIL',
        'UTI_DATE_NAISSANCE',
        'password',
    ];

    /**
     * Les attributs cachés (pour la sécurité).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Typage des colonnes.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'UTI_DATE_NAISSANCE' => 'date',
        ];
    }

    /**
     * Laravel cherche par défaut une colonne 'email'. 
     * On lui dit d'utiliser 'UTI_EMAIL' à la place.
     */
    public function getEmailAttribute()
    {
        return $this->UTI_EMAIL;
    }
}