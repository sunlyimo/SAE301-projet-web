<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Raid extends Model
{
    public static function getFuturRaid() {

        $raids = Raid::where("RAI_RAID_DATE_DEBUT", ">", now())->get(); // Le now et le > ne marhce peut être pas
        return $raids;
    }

    protected $table = 'vik_raid';
    
    protected $primaryKey = 'RAI_ID';

    public $timestamps = false;

    protected $fillable = [
        'RAI_ID', 'CLU_ID', 'UTI_ID', 'RAI_NOM', 
        'RAI_RAID_DATE_DEBUT', 'RAI_RAID_DATE_FIN', 
        'RAI_INSCRI_DATE_DEBUT', 'RAI_INSCRI_DATE_FIN',
        'RAI_CONTACT', 'RAI_WEB', 'RAI_LIEU', 'RAI_IMAGE'
    ];
    
}
