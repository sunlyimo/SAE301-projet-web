<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $table = 'VIK_CLUB';
    protected $primaryKey = 'CLU_ID';
    public $timestamps = false;

    protected $fillable = [
        'CLU_NOM',
        'CLU_VILLE',
        'CLU_CODE_POSTAL',
        'CLU_ADRESSE',
        'CLU_CONTACT',
        'CLU_WEB',
        'CLU_IMAGE',
    ];

    public function raids()
    {
        return $this->hasMany(Raid::class, 'CLU_ID', 'CLU_ID');
    }
}
