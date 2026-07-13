<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create consultation');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string',
            'diagnostic' => 'nullable|string',
            'traitement' => 'nullable|string',
            'ordonnance' => 'nullable|string',
            'prix' => 'required|numeric',
        ];
    }
}
