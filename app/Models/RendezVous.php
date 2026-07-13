<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends BaseModel
{

    protected $table = 'rendez_vouses';

    protected $fillable = [
        'patient_id',
        'date_heure',
        'motif',
        'statut',
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
