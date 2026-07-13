<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaboratoryAnalysis extends BaseModel
{
    protected $fillable = [
        'patient_id',
        'consultation_id',
        'requested_by',
        'analysis_type',
        'description',
        'status',
        'results',
        'validated_by',
        'validated_at',
        'requested_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'validated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
