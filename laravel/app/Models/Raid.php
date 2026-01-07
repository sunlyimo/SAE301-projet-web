<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Raid extends Model
{
    public static function getFuturRaid() {
        $raids = DB::table("vik_raid")
        ->selectRaw("vik_raid.RAI_ID, RAI_NOM , RAI_INSCRI_DATE_DEBUT , RAI_INSCRI_DATE_FIN , RAI_RAID_DATE_DEBUT , RAI_RAID_DATE_FIN, RAI_LIEU, count(cou_id) as total_course")
        ->leftJoin("vik_course","vik_course.RAI_ID","=","vik_raid.RAI_ID")
        ->where("RAI_RAID_DATE_DEBUT", ">", now())
        ->groupBy("vik_raid.RAI_ID","RAI_NOM" , "RAI_INSCRI_DATE_DEBUT" ,"RAI_INSCRI_DATE_FIN" , "RAI_RAID_DATE_DEBUT" , "RAI_RAID_DATE_FIN" , "RAI_LIEU")
        ->get();
        return $raids;
    }

    public static function getFuturRaidA() {
        $raids = DB::table("vik_raid")
        ->selectRaw("vik_raid.RAI_ID, RAI_NOM , RAI_INSCRI_DATE_DEBUT , RAI_INSCRI_DATE_FIN , RAI_RAID_DATE_DEBUT , RAI_RAID_DATE_FIN, RAI_LIEU")
        ->where("RAI_RAID_DATE_DEBUT", ">", now())
        ->get();
        return $raids;
    }

    public static function getFuturRaidTop5() {
        $raids = DB::table("vik_course")
        ->selectRaw("vik_raid.RAI_ID, RAI_NOM , RAI_INSCRI_DATE_DEBUT , RAI_INSCRI_DATE_FIN , RAI_RAID_DATE_DEBUT , RAI_RAID_DATE_FIN, RAI_LIEU , count(*) as total_course")
        ->join("vik_raid","vik_course.RAI_ID","=","vik_raid.RAI_ID")
        ->where("RAI_RAID_DATE_DEBUT", ">", now())
        ->groupBy("vik_raid.RAI_ID","RAI_NOM" , "RAI_INSCRI_DATE_DEBUT" ,"RAI_INSCRI_DATE_FIN" , "RAI_RAID_DATE_DEBUT" , "RAI_RAID_DATE_FIN", "RAI_LIEU")
        ->orderBy("RAI_RAID_DATE_DEBUT")
        ->get();
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
