<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrancheDifficulte extends Model
{
    protected $table = 'vik_tranche_difficulte';
    protected $primaryKey = 'DIF_NIVEAU';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'DIF_NIVEAU',
        'DIF_DESCRIPTION',
    ];
}
