<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaboratoryAnalysisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create lab request');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'analysis_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
