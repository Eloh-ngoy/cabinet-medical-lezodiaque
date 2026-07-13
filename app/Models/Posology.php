<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Posology extends BaseModel
{

    protected $fillable = [
        'hospitalization_id',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function hospitalization(): BelongsTo
    {
        return $this->belongsTo(Hospitalization::class);
    }
}
