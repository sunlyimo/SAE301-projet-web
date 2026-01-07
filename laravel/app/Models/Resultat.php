<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    protected $table = 'vik_resultat';
    protected $primaryKey = ['RAI_ID', 'COU_ID', 'EQU_ID'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'RAI_ID', 
        'COU_ID', 
        'EQU_ID', 
        'RES_RANG', 
        'RES_TEMPS', 
        'RES_POINT'
    ];

    /**
     * Permet à Laravel de faire des UPDATE ou DELETE corrects
     */
    protected function setKeysForSaveQuery($query)
    {
        return $query->where('RAI_ID', $this->getAttribute('RAI_ID'))
                     ->where('COU_ID', $this->getAttribute('COU_ID'))
                     ->where('EQU_ID', $this->getAttribute('EQU_ID'));
    }

    /**
     * Relation : Un résultat appartient à une Équipe
     */
    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'EQU_ID', 'EQU_ID')
                    ->where('RAI_ID', $this->getAttribute('RAI_ID'))
                    ->where('COU_ID', $this->getAttribute('COU_ID'));
    }

    /**
     * Relation : Un résultat appartient à une Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'COU_ID', 'COU_ID')
                    ->where('RAI_ID', $this->getAttribute('RAI_ID'));
    }
}