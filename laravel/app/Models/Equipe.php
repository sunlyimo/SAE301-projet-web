<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    protected $table = 'vik_equipe';
    protected $primaryKey = ['RAI_ID', 'COU_ID', 'EQU_ID'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'RAI_ID', 'COU_ID', 'EQU_ID', 'UTI_ID', 
        'EQU_NOM', 'EQU_IMAGE'
    ];

    protected function setKeysForSaveQuery($query)
    {
        return $query->where('RAI_ID', $this->getAttribute('RAI_ID'))
                     ->where('COU_ID', $this->getAttribute('COU_ID'))
                     ->where('EQU_ID', $this->getAttribute('EQU_ID'));
    }

    public function chef()
    {
        return $this->belongsTo(User::class, 'UTI_ID', 'UTI_ID');
    }

    public function membres()
    {
        return $this->hasMany(Appartient::class, 'EQU_ID', 'EQU_ID');
    }

    public function getCourseAttribute()
    {
        return \App\Models\Course::where('RAI_ID', $this->getAttribute('RAI_ID'))
                    ->where('COU_ID', $this->getAttribute('COU_ID'))
                    ->first();
    }
}