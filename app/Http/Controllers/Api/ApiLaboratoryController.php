<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaboratoryAnalysisResource;
use App\Models\LaboratoryAnalysis;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ApiLaboratoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', LaboratoryAnalysis::class);

        $status = $request->get('status');
        $analyses = LaboratoryAnalysis::with(['patient', 'requestedBy', 'validatedBy'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('requested_at', 'desc')
            ->paginate(15);

        return LaboratoryAnalysisResource::collection($analyses);
    }

    public function show(LaboratoryAnalysis $laboratoryAnalysis): LaboratoryAnalysisResource
    {
        $this->authorize('view', $laboratoryAnalysis);
        $laboratoryAnalysis->load(['patient', 'requestedBy', 'validatedBy']);

        return new LaboratoryAnalysisResource($laboratoryAnalysis);
    }

    public function store(Request $request): LaboratoryAnalysisResource
    {
        $this->authorize('create', LaboratoryAnalysis::class);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'analysis_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $analysis = LaboratoryAnalysis::create([
            'patient_id' => $validated['patient_id'],
            'consultation_id' => $validated['consultation_id'] ?? null,
            'requested_by' => Auth::id(),
            'analysis_type' => $validated['analysis_type'],
            'description' => $validated['description'] ?? null,
            'status' => 'demandee',
            'requested_at' => now(),
        ]);

        return new LaboratoryAnalysisResource($analysis);
    }

    public function enterResults(Request $request, LaboratoryAnalysis $laboratoryAnalysis): LaboratoryAnalysisResource
    {
        $this->authorize('enterResults', $laboratoryAnalysis);

        $validated = $request->validate([
            'results' => 'required|string',
        ]);

        $laboratoryAnalysis->update([
            'results' => $validated['results'],
            'status' => 'terminee',
            'completed_at' => now(),
        ]);

        return new LaboratoryAnalysisResource($laboratoryAnalysis);
    }

    public function validateAnalysis(LaboratoryAnalysis $laboratoryAnalysis): LaboratoryAnalysisResource
    {
        $this->authorize('validateResults', $laboratoryAnalysis);

        $laboratoryAnalysis->update([
            'status' => 'validee',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        return new LaboratoryAnalysisResource($laboratoryAnalysis);
    }
}
