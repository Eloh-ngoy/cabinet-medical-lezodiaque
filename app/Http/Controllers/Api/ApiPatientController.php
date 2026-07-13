<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiPatientController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Patient::class);

        $search = $request->get('search');
        $patients = Patient::when($search, function ($query) use ($search) {
            return $query->where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('numero_unique', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return PatientResource::collection($patients);
    }

    public function show(Patient $patient): PatientResource
    {
        $this->authorize('view', $patient);
        $patient->load(['consultations.user', 'hospitalizations.bed', 'rendezVous', 'laboratoryAnalyses']);

        return new PatientResource($patient);
    }

    public function store(Request $request): PatientResource
    {
        $this->authorize('create', Patient::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:patients,email',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:homme,femme',
            'groupe_sanguin' => 'nullable|string|max:5',
            'statut_interne_externe' => 'required|in:interne,externe',
            'traitement_passe' => 'nullable|string',
            'adresse' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string|max:100',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'allergies' => 'nullable|array',
            'antecedents' => 'nullable|array',
            'maladies_chroniques' => 'nullable|array',
        ]);

        return new PatientResource(Patient::create($validated));
    }

    public function update(Request $request, Patient $patient): PatientResource
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:homme,femme',
            'groupe_sanguin' => 'nullable|string|max:5',
            'statut_interne_externe' => 'required|in:interne,externe',
            'traitement_passe' => 'nullable|string',
            'adresse' => 'nullable|string',
            'contact_urgence_nom' => 'nullable|string|max:100',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'allergies' => 'nullable|array',
            'antecedents' => 'nullable|array',
            'maladies_chroniques' => 'nullable|array',
        ]);

        $patient->update($validated);

        return new PatientResource($patient);
    }

    public function destroy(Patient $patient): \Illuminate\Http\Response
    {
        $this->authorize('delete', $patient);
        $patient->delete();

        return response()->noContent();
    }
}
