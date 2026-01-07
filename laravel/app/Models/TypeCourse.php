<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCourse extends Model
{
    protected $table = 'vik_course_type'; 
    protected $primaryKey = 'TYP_ID';
    public $incrementing = true;
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