<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponsableClub extends Model
{
    protected $table = 'vik_responsable_club';
    protected $primaryKey = 'UTI_ID';
    public $timestamps = false;
    // Primary key is not auto-incrementing in this table
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'UTI_ID',
        'CLU_ID',
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

    public function club()
    {
        return $this->belongsTo(Club::class, 'CLU_ID', 'CLU_ID');
    }
}
