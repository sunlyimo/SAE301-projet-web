<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model
{
    use HasFactory;
    protected $table = 'vik_club';
    protected $primaryKey = 'CLU_ID';

    protected $fillable = [
        'CLU_NOM',
        'CLU_RUE',
        'CLU_CODE_POSTAL',
        'CLU_VILLE',
    ];

    public function responsable()
    {
        return $this->hasOne(\App\Models\ResponsableClub::class, 'CLU_ID', 'CLU_ID');
    }

    public function raids()
    {
        return $this->hasMany(\App\Models\Raid::class, 'CLU_ID', 'CLU_ID');
    }

    /**
     * Membres (coureurs) du club
     */
    public function coureurs()
    {
        return $this->hasMany(\App\Models\Coureur::class, 'CLU_ID', 'CLU_ID');
    }
}


