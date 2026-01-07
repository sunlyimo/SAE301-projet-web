<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCourse extends Model
{
    // Nom de la table défini dans ta migration
    protected $table = 'vik_course_type'; 

    // Clé primaire
    protected $primaryKey = 'TYP_ID';

    // Puisque tu utilises $table->increments(), c'est true par défaut
    public $incrementing = true;

    // Pas de colonnes created_at/updated_at dans ta migration
    public $timestamps = false;

    protected $fillable = [
        'TYP_DESCRIPTION'
    ];

    /**
     * Relation avec les courses
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'TYP_ID', 'TYP_ID');
    }
}