<?php

namespace App\Services;

use App\Models\LaboratoryAnalysis;
use Illuminate\Support\Facades\Auth;

class LaboratoryService
{
    public function getPaginatedAnalyses(?string $status = null, int $perPage = 10)
    {
        return LaboratoryAnalysis::with(['patient', 'requestedBy', 'validatedBy'])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('requested_at', 'desc')
            ->paginate($perPage);
    }

    public function createAnalysis(array $data): LaboratoryAnalysis
    {
        return LaboratoryAnalysis::create([
            'patient_id' => $data['patient_id'],
            'consultation_id' => $data['consultation_id'] ?? null,
            'requested_by' => Auth::id(),
            'analysis_type' => $data['analysis_type'],
            'description' => $data['description'] ?? null,
            'status' => 'demandee',
            'requested_at' => now(),
        ]);
    }

    public function enterResults(LaboratoryAnalysis $analysis, string $results): LaboratoryAnalysis
    {
        $analysis->update([
            'results' => $results,
            'status' => 'terminee',
            'completed_at' => now(),
        ]);

        return $analysis;
    }

    public function validateAnalysis(LaboratoryAnalysis $analysis): LaboratoryAnalysis
    {
        $analysis->update([
            'status' => 'validee',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        return $analysis;
    }
}
