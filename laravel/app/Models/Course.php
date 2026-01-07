<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $table = 'VIK_COURSE';
    protected $primaryKey = ['RAI_ID', 'COU_ID'];
    
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'RAI_ID', 'COU_ID', 'TYP_ID', 'DIF_NIVEAU', 'UTI_ID', 
        'COU_NOM', 'COU_DATE_DEBUT', 'COU_DATE_FIN', 'COU_PRIX', 
        'COU_LIEU', 'COU_AGE_MIN', 'COU_AGE_SEUL', 'COU_AGE_ACCOMPAGNATEUR',
        'COU_PARTICIPANT_MIN', 'COU_PARTICIPANT_MAX', 'COU_EQUIPE_MIN', 
        'COU_EQUIPE_MAX', 'COU_PARTICIPANT_PAR_EQUIPE_MAX', 'COU_REDUCTION', 'COU_PRIX_ENFANT', 'COU_REPAS_PRIX',
    ];

    /**
     * Correction pour gérer les clés primaires composées lors de l'UPDATE
     * Cette méthode indique à Eloquent comment identifier la ligne de manière unique
     */
    protected function setKeysForSaveQuery($query)
    {
        return $query->where('RAI_ID', $this->getAttribute('RAI_ID'))
                     ->where('COU_ID', $this->getAttribute('COU_ID'));
    }

    /**
     * Relation avec le Responsable (Utilisateur)
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UTI_ID', 'UTI_ID');
    }

    /**
     * Relation avec le Type de Course
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeCourse::class, 'TYP_ID', 'TYP_ID');
    }

    public function equipeDuUser()
    {
        $userId = auth()->id();
        if (!$userId) return null;

        $equipeChef = \App\Models\Equipe::where('RAI_ID', $this->RAI_ID)
                        ->where('COU_ID', $this->COU_ID)
                        ->where('UTI_ID', $userId)
                        ->first();

        if ($equipeChef) return $equipeChef;

        $appartient = \App\Models\Appartient::where('RAI_ID', $this->RAI_ID)
                        ->where('COU_ID', $this->COU_ID)
                        ->where('UTI_ID', $userId)
                        ->first();

        if ($appartient) {
            return \App\Models\Equipe::where('RAI_ID', $this->RAI_ID)
                        ->where('COU_ID', $this->COU_ID)
                        ->where('EQU_ID', $appartient->EQU_ID)
                        ->first();
        }

        return null;
    }
}