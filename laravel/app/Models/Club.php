<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $table = 'vik_club';

    protected $primaryKey = 'CLU_ID';

    protected $fillable = [
        'CLU_NOM',
        'CLU_RUE',
        'CLU_CODE_POSTAL',
        'CLU_VILLE',
    ];
}
