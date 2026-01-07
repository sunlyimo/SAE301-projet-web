<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    protected $table = 'vik_administrateur';

    protected $primaryKey = 'UTI_ID';

    protected $fillable = [
        'UTI_ID',
        'UTI_EMAIL',
        'UTI_NOM',
        'UTI_PRENOM',
        'UTI_DATE_NAISSANCE',
        'UTI_RUE',
        'UTI_CODE_POSTAL',
        'UTI_VILLE',
        'UTI_TELEPHONE',
        'UTI_LICENCE',
        'UTI_NOM_UTILISATEUR',
        'UTI_MOT_DE_PASSE',
    ];

    public $timestamps = false;

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'UTI_ID', 'UTI_ID');
    }
}
