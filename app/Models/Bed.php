<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Bed extends BaseModel
{

    protected $fillable = [
        'bed_number',
        'bed_type',
        'room_number',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function hospitalizations(): HasMany
    {
        return $this->hasMany(Hospitalization::class);
    }

    public function label(): string
    {
        return 'Lit ' . $this->bed_number . ' - Chambre ' . $this->room_number;
    }
}
