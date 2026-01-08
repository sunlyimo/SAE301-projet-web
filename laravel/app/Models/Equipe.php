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

    /**
     * Relation avec les membres de l'équipe via la table vik_appartient
     */
    public function membres()
    {
        return $this->hasMany(Appartient::class, 'EQU_ID', 'EQU_ID');
    }

    // Cette fonction remplace la relation "utilisateurs" qui plantait.
    // Elle fait une jointure manuelle pour être sûre de récupérer les bons membres.
    public function getMembresListAttribute()
    {
        return User::join('vik_appartient', 'vik_utilisateur.UTI_ID', '=', 'vik_appartient.UTI_ID')
                   ->where('vik_appartient.EQU_ID', $this->getAttribute('EQU_ID'))
                   ->where('vik_appartient.RAI_ID', $this->getAttribute('RAI_ID'))
                   ->where('vik_appartient.COU_ID', $this->getAttribute('COU_ID'))
                   ->select('vik_utilisateur.*')
                   ->get();
    }

    public function getResultatCacheAttribute()
    {
        return Resultat::where('RAI_ID', $this->getAttribute('RAI_ID'))
                       ->where('COU_ID', $this->getAttribute('COU_ID'))
                       ->where('EQU_ID', $this->getAttribute('EQU_ID'))
                       ->first();
    }

    /**
     * Relation avec la course à laquelle l'équipe participe.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, ['RAI_ID', 'COU_ID'], ['RAI_ID', 'COU_ID']);
    }
}