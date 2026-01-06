<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = 'vik_club';

    protected $primaryKey = 'CLU_ID';

    public $timestamps = false;

    protected $fillable = [
        'CLU_NOM',
        'UTI_RUE',
        'UTI_CODE_POSTAL',
        'UTI_VILLE',
    ];
}
