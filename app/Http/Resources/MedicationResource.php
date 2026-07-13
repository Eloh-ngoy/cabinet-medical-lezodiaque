<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'category' => $this->category,
            'unit' => $this->unit,
            'stock_quantity' => $this->stock_quantity,
            'min_stock_threshold' => $this->min_stock_threshold,
            'unit_price' => $this->unit_price,
            'is_low_stock' => $this->isLowStock(),
            'description' => $this->description,
        ];
    }
}
