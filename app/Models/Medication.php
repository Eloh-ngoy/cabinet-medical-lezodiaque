<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medication extends BaseModel
{
    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'unit',
        'stock_quantity',
        'min_stock_threshold',
        'unit_price',
        'description',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'min_stock_threshold' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(MedicationMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_threshold;
    }
}
