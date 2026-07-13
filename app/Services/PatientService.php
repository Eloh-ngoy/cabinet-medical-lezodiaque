<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientService
{
    public function searchPatients(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Patient::when($search, function ($query) use ($search) {
            return $query->where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('numero_unique', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function createPatient(array $data): Patient
    {
        return Patient::create($data);
    }

    public function updatePatient(Patient $patient, array $data): Patient
    {
        $patient->update($data);
        return $patient;
    }

    public function getPatientWithRelations(Patient $patient): Patient
    {
        return $patient->load(['consultations.user', 'rendezVous', 'hospitalizations.bed', 'laboratoryAnalyses']);
    }
}
