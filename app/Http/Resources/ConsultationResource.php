<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'user' => new UserResource($this->whenLoaded('user')),
            'date_consultation' => $this->date_consultation?->toISOString(),
            'motif' => $this->motif,
            'diagnostic' => $this->diagnostic,
            'traitement' => $this->traitement,
            'prix' => $this->prix,
            'ordonnance' => $this->ordonnance,
        ];
    }
}
