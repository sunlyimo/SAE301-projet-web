<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Raid extends Model
{
    public function getFuturRaid() {
        $raids = DB::table("VIK_RAID")        
                    ->whereRaw('rai_raid_date_debut > sysdate')
                    ->get();
        return $raids;
    }

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
