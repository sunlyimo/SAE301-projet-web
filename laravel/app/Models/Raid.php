<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raid extends Model
{
    protected $table = 'VIK_RAID';
    
    protected $primaryKey = 'RAI_ID';

    public $timestamps = false;

    protected $fillable = [
        'RAI_ID', 'CLU_ID', 'UTI_ID', 'RAI_NOM', 
        'RAI_RAID_DATE_DEBUT', 'RAI_RAID_DATE_FIN', 
        'RAI_INSCRI_DATE_DEBUT', 'RAI_INSCRI_DATE_FIN',
        'RAI_CONTACT', 'RAI_WEB', 'RAI_LIEU', 'RAI_IMAGE'
    ];
    
}