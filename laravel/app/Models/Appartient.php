<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appartient extends Model
{
    protected $table = 'vik_appartient';
    
    protected $primaryKey = ['UTI_ID', 'RAI_ID', 'COU_ID', 'EQU_ID'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['UTI_ID', 'RAI_ID', 'COU_ID', 'EQU_ID'];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'UTI_ID', 'UTI_ID');
    }
}