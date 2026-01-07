<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    public static function getRaceOfRaid($id)
    {
        $courses = DB::table("vik_course")->where("RAI_ID","=",$id)->get();
        return $courses;
    }


    protected $table = 'vik_course'; 
    
    protected $primaryKey = ['RAI_ID', 'COU_ID'];
    public $incrementing = false;

    protected $fillable = [
        'RAI_ID', 'COU_ID', 'TYP_ID', 'DIF_NIVEAU', 'UTI_ID', 
        'COU_NOM', 'COU_DATE_DEBUT', 'COU_DATE_FIN', 'COU_PRIX', 
        'COU_LIEU', 'COU_AGE_MIN', 'COU_AGE_SEUL', 'COU_AGE_ACCOMPAGNATEUR',
    ];
}