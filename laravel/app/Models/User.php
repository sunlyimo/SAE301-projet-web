<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ResponsableClub;
use App\Models\Club;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'vik_utilisateur';
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
        'UTI_MOT_DE_PASSE',
        'UTI_NOM_UTILISATEUR',
        'UTI_RUE',
        'UTI_CODE_POSTAL',
        'UTI_VILLE',
        'UTI_TELEPHONE',
        'UTI_LICENCE',
    ];

    /**
     * Les attributs cachés (pour la sécurité).
     */
    protected $hidden = [
        'UTI_MOT_DE_PASSE',
        'remember_token',
    ];

    /**
     * Typage des colonnes.
     */
    protected function casts(): array
    {
        return [
            'UTI_MOT_DE_PASSE' => 'hashed',
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

    /**
     * Indique à Laravel que la colonne du mot de passe est UTI_MOT_DE_PASSE
     */
    public function getAuthPassword()
    {
        return $this->UTI_MOT_DE_PASSE;
    }

    public function clubs()
    {
    return $this->belongsToMany(
        Club::class,
        'vik_responsable_club',
        'UTI_ID',
        'CLU_ID'
    );
    }

    /**
     * Relation avec le club via la table vik_coureur
     */
    public function clubAsCoureur()
    {
        return $this->hasOne(Coureur::class, 'UTI_ID', 'UTI_ID');
    }

    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public function isAdmin()
    {
        return Administrateur::where('UTI_ID', $this->UTI_ID)->exists();
    }

    /**
     * Vérifie si l'utilisateur est responsable d'un club
     */
    public function isResponsable()
    {
        return ResponsableClub::where('UTI_ID', $this->UTI_ID)->exists();
    }

    /**
     * Récupère le club dont l'utilisateur est responsable
     */
    public function getClub()
    {
        $responsable = ResponsableClub::where('UTI_ID', $this->UTI_ID)->first();
        return $responsable ? $responsable->club : null;
    }

    /**
     * Vérifie si l'utilisateur est responsable d'un club spécifique
     */
    public function isResponsableOf(Club $club)
    {
        return ResponsableClub::where('UTI_ID', $this->UTI_ID)
            ->where('CLU_ID', $club->CLU_ID)
            ->exists();
    }

    public function resultats()
    {

        return $this->hasManyThrough(
            Resultat::class, 
            Appartient::class,
            'UTI_ID',
            'EQU_ID',
            'UTI_ID',
            'EQU_ID'
        );
    }

    /**
     * Relation avec la table Coureur (pour accéder au CRR_PPS)
     */
    public function coureur()
    {
        return $this->hasOne(Coureur::class, 'UTI_ID', 'UTI_ID');
    }
}