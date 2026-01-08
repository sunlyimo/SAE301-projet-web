<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Raid extends Model
{
    public static function getFuturRaid() {
        $raids = DB::table("vik_raid")
        ->selectRaw("vik_raid.RAI_ID, RAI_NOM , RAI_INSCRI_DATE_DEBUT , RAI_INSCRI_DATE_FIN , RAI_RAID_DATE_DEBUT , RAI_RAID_DATE_FIN, RAI_LIEU, count(cou_id) as total_course, CONCAT(UTI_PRENOM, ' ', UTI_NOM) as responsable_nom")
        ->leftJoin("vik_course","vik_course.RAI_ID","=","vik_raid.RAI_ID")
        ->leftJoin("vik_utilisateur","vik_utilisateur.UTI_ID","=","vik_raid.UTI_ID")
        ->where("RAI_RAID_DATE_DEBUT", ">", now())
        ->groupBy("vik_raid.RAI_ID","RAI_NOM" , "RAI_INSCRI_DATE_DEBUT" ,"RAI_INSCRI_DATE_FIN" , "RAI_RAID_DATE_DEBUT" , "RAI_RAID_DATE_FIN" , "RAI_LIEU", "responsable_nom")
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

    public static function getAllRaids() {
        $raids = DB::table("vik_raid")
        ->selectRaw("
            vik_raid.RAI_ID,
            vik_raid.UTI_ID,
            RAI_NOM, 
            RAI_INSCRI_DATE_DEBUT, 
            RAI_INSCRI_DATE_FIN, 
            RAI_RAID_DATE_DEBUT, 
            RAI_RAID_DATE_FIN, 
            RAI_LIEU, 
            count(vik_course.cou_id) as total_course, 
            CONCAT(vik_utilisateur.UTI_PRENOM, ' ', vik_utilisateur.UTI_NOM) as responsable_nom
        ")
        ->leftJoin("vik_course","vik_course.RAI_ID","=","vik_raid.RAI_ID")
        ->leftJoin("vik_utilisateur","vik_utilisateur.UTI_ID","=","vik_raid.UTI_ID")
        ->groupBy(
            "vik_raid.RAI_ID","vik_raid.UTI_ID","RAI_NOM", "RAI_INSCRI_DATE_DEBUT", 
            "RAI_INSCRI_DATE_FIN", "RAI_RAID_DATE_DEBUT", 
            "RAI_RAID_DATE_FIN", "RAI_LIEU", "responsable_nom"
        )
        ->orderByDesc("RAI_RAID_DATE_DEBUT") 
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

    protected $casts = [
        'RAI_RAID_DATE_DEBUT' => 'datetime',
        'RAI_RAID_DATE_FIN' => 'datetime',
        'RAI_INSCRI_DATE_DEBUT' => 'datetime',
        'RAI_INSCRI_DATE_FIN' => 'datetime',
    ];

    /**
     * Relation avec le responsable (Utilisateur)
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'UTI_ID', 'UTI_ID');
    }

    /**
     * Relation avec le club organisateur
     */
    public function club()
    {
        return $this->belongsTo(Club::class, 'CLU_ID', 'CLU_ID');
    }
}
