<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LaboratoryAnalysisResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'requested_by' => new UserResource($this->whenLoaded('requestedBy')),
            'analysis_type' => $this->analysis_type,
            'description' => $this->description,
            'status' => $this->status,
            'results' => $this->results,
            'validated_by' => new UserResource($this->whenLoaded('validatedBy')),
            'requested_at' => $this->requested_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'validated_at' => $this->validated_at?->toISOString(),
        ];
    }
}
