<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raid extends Model
{
    public function getFuturRaid() {
        $raids = DB::table("VIK_RAID")        
                    ->whereRaw('rai_raid_date_debut > sysdate')
                    ->get();
        return $raids;
    }
}
