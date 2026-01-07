<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponsableInvitation extends Model
{
    protected $table = 'responsable_invitations';

    protected $fillable = [
        'club_id',
        'user_id',
        'token',
        'status',
        'accepted_at',
        'refused_at',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id', 'CLU_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'UTI_ID');
    }
}