<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coureur extends Model
{
    use HasFactory;
    protected $table = 'vik_coureur';
    protected $primaryKey = 'UTI_ID';
    public $timestamps = false;
    protected $fillable = [
        'UTI_ID',
        'CLU_ID',
        'CRR_PPS',
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

    /**
     * Relation avec le club
     */
    public function club()
    {
        return $this->belongsTo(Club::class, 'CLU_ID', 'CLU_ID');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UTI_ID', 'UTI_ID');
    }
}
