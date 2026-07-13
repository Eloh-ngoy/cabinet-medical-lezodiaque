<?php

namespace App\Services;

use App\Models\Bed;
use App\Models\Hospitalization;
use Illuminate\Support\Facades\DB;

class HospitalizationService
{
    public function getPaginatedHospitalizations(?string $status = null, int $perPage = 10)
    {
        return Hospitalization::with(['patient', 'bed'])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('admission_date', 'desc')
            ->paginate($perPage);
    }

    public function createHospitalization(array $data): Hospitalization
    {
        return DB::transaction(function () use ($data) {
            $hospitalization = Hospitalization::create($data);

            Bed::where('id', $data['bed_id'])->update(['is_available' => false]);

            return $hospitalization;
        });
    }

    public function dischargeHospitalization(Hospitalization $hospitalization, array $data): Hospitalization
    {
        return DB::transaction(function () use ($hospitalization, $data) {
            $hospitalization->update([
                'status' => 'discharged',
                'discharge_date' => $data['discharge_date'],
                'discharge_notes' => $data['discharge_notes'] ?? null,
            ]);

            Bed::where('id', $hospitalization->bed_id)->update(['is_available' => true]);

            return $hospitalization;
        });
    }
}
