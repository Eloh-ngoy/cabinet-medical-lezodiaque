<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalizationResource;
use App\Models\Bed;
use App\Models\Hospitalization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiHospitalizationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Hospitalization::class);

        $status = $request->get('status');
        $hospitalizations = Hospitalization::with(['patient', 'bed'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('admission_date', 'desc')
            ->paginate(15);

        return HospitalizationResource::collection($hospitalizations);
    }

    public function show(Hospitalization $hospitalization): HospitalizationResource
    {
        $this->authorize('view', $hospitalization);
        $hospitalization->load(['patient', 'bed', 'posologies']);

        return new HospitalizationResource($hospitalization);
    }

    public function store(Request $request): HospitalizationResource
    {
        $this->authorize('create', Hospitalization::class);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_date' => 'required|date',
            'expected_duration' => 'nullable|integer|min:1',
            'admission_reason' => 'nullable|string',
        ]);

        $hospitalization = Hospitalization::create($validated);
        Bed::where('id', $validated['bed_id'])->update(['is_available' => false]);

        return new HospitalizationResource($hospitalization);
    }

    public function update(Request $request, Hospitalization $hospitalization): HospitalizationResource
    {
        $this->authorize('update', $hospitalization);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_date' => 'required|date',
            'expected_duration' => 'nullable|integer|min:1',
            'admission_reason' => 'nullable|string',
        ]);

        $hospitalization->update($validated);

        return new HospitalizationResource($hospitalization);
    }

    public function discharge(Request $request, Hospitalization $hospitalization): HospitalizationResource
    {
        $this->authorize('discharge', $hospitalization);

        $validated = $request->validate([
            'discharge_date' => 'required|date',
            'discharge_notes' => 'nullable|string',
        ]);

        $hospitalization->update([
            'status' => 'discharged',
            'discharge_date' => $validated['discharge_date'],
            'discharge_notes' => $validated['discharge_notes'] ?? null,
        ]);

        Bed::where('id', $hospitalization->bed_id)->update(['is_available' => true]);

        return new HospitalizationResource($hospitalization);
    }

    public function destroy(Hospitalization $hospitalization): \Illuminate\Http\Response
    {
        $this->authorize('delete', $hospitalization);
        $hospitalization->delete();

        return response()->noContent();
    }
}
