<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHospitalizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create hospitalization');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_date' => 'required|date',
            'expected_duration' => 'nullable|integer|min:1',
            'admission_reason' => 'nullable|string',
        ];
    }
}
