<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends BaseModel
{

    protected $fillable = [
        'patient_id',
        'user_id',
        'date_consultation',
        'motif',
        'diagnostic',
        'traitement',
        'prix',
        'ordonnance',
    ];

    protected $casts = [
        'date_consultation' => 'datetime',
        'prix' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
