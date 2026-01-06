<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raid extends Model
{
    protected $table = 'VIK_RAID';

    protected $primaryKey = 'RAI_ID';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'CLU_ID',
        'UTI_ID',
        'RAI_NOM',
        'RAI_RAID_DATE_DEBUT',
        'RAI_RAID_DATE_FIN',
        'RAI_INSCRI_DATE_DEBUT',
        'RAI_INSCRI_DATE_FIN',
        'RAI_CONTACT',
        'RAI_WEB',
        'RAI_LIEU',
        'RAI_IMAGE',
    ];

    protected $casts = [
        'RAI_RAID_DATE_DEBUT' => 'datetime',
        'RAI_RAID_DATE_FIN' => 'datetime',
        'RAI_INSCRI_DATE_DEBUT' => 'datetime',
        'RAI_INSCRI_DATE_FIN' => 'datetime',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'CLU_ID', 'CLU_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UTI_ID', 'UTI_ID');
    }
}
