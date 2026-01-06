<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'VIK_COURSE'; 
    
    protected $primaryKey = ['RAI_ID', 'COU_ID'];
    public $incrementing = false;

    protected $fillable = [
        'RAI_ID', 'COU_ID', 'TYP_ID', 'DIF_NIVEAU', 'UTI_ID', 
        'COU_NOM', 'COU_DATE_DEBUT', 'COU_DATE_FIN', 'COU_PRIX', 
        'COU_LIEU', 'COU_AGE_MIN', 'COU_AGE_SEUL', 'COU_AGE_ACCOMPAGNATEUR',
    ];
}