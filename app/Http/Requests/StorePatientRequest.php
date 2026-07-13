<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create patient');
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:patients,email',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:homme,femme',
            'groupe_sanguin' => 'nullable|string|max:5',
            'statut_interne_externe' => 'required|in:interne,externe',
            'traitement_passe' => 'nullable|string',
            'adresse' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string|max:100',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'allergies' => 'nullable|array',
            'antecedents' => 'nullable|array',
            'maladies_chroniques' => 'nullable|array',
        ];
    }
}
