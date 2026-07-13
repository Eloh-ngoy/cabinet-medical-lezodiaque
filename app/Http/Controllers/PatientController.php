<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct(private PatientService $patientService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('view patients')) {
            abort(403);
        }

        $search = $request->get('search');
        $patients = $this->patientService->searchPatients($search);

        return view('patients.index', compact('patients', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->can('create patient')) {
            abort(403);
        }

        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('create patient')) {
            abort(403);
        }

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

        $this->patientService->createPatient($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        if (!Auth::user()->can('view patients')) {
            abort(403);
        }

        $patient->load(['consultations', 'rendezVous', 'hospitalizations']);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        if (!Auth::user()->can('edit patient')) {
            abort(403);
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        if (!Auth::user()->can('edit patient')) {
            abort(403);
        }

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

        $this->patientService->updatePatient($patient, $validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        if (!Auth::user()->can('edit patient')) {
            abort(403);
        }

        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient supprimé avec succès.');
    }
}
