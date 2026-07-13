<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ApiConsultationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Consultation::class);

        $consultations = Consultation::with('patient')
            ->orderBy('date_consultation', 'desc')
            ->paginate(15);

        return ConsultationResource::collection($consultations);
    }

    public function show(Consultation $consultation): ConsultationResource
    {
        $this->authorize('view', $consultation);
        $consultation->load(['patient', 'user']);

        return new ConsultationResource($consultation);
    }

    public function store(Request $request): ConsultationResource
    {
        $this->authorize('create', Consultation::class);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string',
            'diagnostic' => 'nullable|string',
            'traitement' => 'nullable|string',
            'ordonnance' => 'nullable|string',
            'prix' => 'required|numeric',
        ]);

        $consultation = Consultation::create($validated + ['user_id' => Auth::id()]);

        return new ConsultationResource($consultation);
    }

    public function update(Request $request, Consultation $consultation): ConsultationResource
    {
        $this->authorize('update', $consultation);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string',
            'diagnostic' => 'nullable|string',
            'traitement' => 'nullable|string',
            'ordonnance' => 'nullable|string',
            'prix' => 'required|numeric',
        ]);

        $consultation->update($validated);

        return new ConsultationResource($consultation);
    }

    public function destroy(Consultation $consultation): \Illuminate\Http\Response
    {
        $this->authorize('delete', $consultation);
        $consultation->delete();

        return response()->noContent();
    }
}
