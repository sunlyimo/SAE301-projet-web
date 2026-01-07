<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoureurRpps extends Model
{
    protected $table = 'vik_coureur_rpps';
    protected $primaryKey = 'CRP_ID';
    public $timestamps = true;

    protected $fillable = [
        'UTI_ID',
        'RAI_ID',
        'COU_ID',
        'CRP_NUMERO_RPPS',
    ];

    // Relation avec Coureur
    public function coureur()
    {
        return $this->belongsTo(Coureur::class, 'UTI_ID', 'UTI_ID');
    }

    // Relation avec Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'COU_ID', 'COU_ID');
    }
}
