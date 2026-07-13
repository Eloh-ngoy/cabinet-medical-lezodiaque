<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospitalization extends BaseModel
{

    protected $fillable = [
        'patient_id',
        'bed_id',
        'admission_date',
        'expected_duration',
        'discharge_date',
        'status',
        'admission_reason',
        'discharge_notes',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'expected_duration' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function posologies(): HasMany
    {
        return $this->hasMany(Posology::class);
    }
}
