<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'bed_label' => $this->bed?->label(),
            'admission_date' => $this->admission_date?->toISOString(),
            'expected_duration' => $this->expected_duration,
            'discharge_date' => $this->discharge_date?->toISOString(),
            'status' => $this->status,
            'admission_reason' => $this->admission_reason,
            'discharge_notes' => $this->discharge_notes,
        ];
    }
}
