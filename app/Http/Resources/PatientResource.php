<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero_unique' => $this->numero_unique,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'date_naissance' => $this->date_naissance?->format('Y-m-d'),
            'sexe' => $this->sexe,
            'groupe_sanguin' => $this->groupe_sanguin,
            'statut_interne_externe' => $this->statut_interne_externe,
            'adresse' => $this->adresse,
            'contact_urgence_nom' => $this->contact_urgence_nom,
            'contact_urgence_telephone' => $this->contact_urgence_telephone,
            'allergies' => $this->allergies,
            'antecedents' => $this->antecedents,
            'maladies_chroniques' => $this->maladies_chroniques,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
