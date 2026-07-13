<?php

namespace App\Services;

use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;

class ConsultationService
{
    public function getPaginatedConsultations(int $perPage = 10)
    {
        return Consultation::with('patient')
            ->orderBy('date_consultation', 'desc')
            ->paginate($perPage);
    }

    public function createConsultation(array $data): Consultation
    {
        return Consultation::create($data + ['user_id' => Auth::id()]);
    }

    public function updateConsultation(Consultation $consultation, array $data): Consultation
    {
        $consultation->update($data);
        return $consultation;
    }
}
