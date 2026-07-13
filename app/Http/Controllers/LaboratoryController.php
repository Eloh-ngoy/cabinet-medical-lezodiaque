<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\LaboratoryAnalysis;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->can('view lab requests')) {
            abort(403);
        }

        $status = $request->get('status');
        $search = $request->get('search');
        $analyses = LaboratoryAnalysis::with(['patient', 'requestedBy', 'validatedBy'])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('numero_unique', 'like', "%{$search}%");
                })->orWhere('analysis_type', 'like', "%{$search}%");
            })
            ->orderBy('requested_at', 'desc')
            ->paginate(10);

        return view('laboratory.index', compact('analyses', 'status', 'search'));
    }

    public function create()
    {
        if (!Auth::user()->can('create lab request')) {
            abort(403);
        }

        $patients = Patient::orderBy('nom')->get();
        $consultations = Consultation::with('patient')->orderBy('date_consultation', 'desc')->get();
        $analysisTypes = [
            'Hémogramme (NFS)',
            'Glycémie',
            'Bilan hépatique',
            'Bilan rénal',
            'Urée',
            'Créatinine',
            'Cholestérol',
            'Triglycérides',
            'Vitamine D',
            'Fer sérique',
            'TSH',
            'Coproculture',
            'Hémoculture',
            'ECBU',
            'Sérologie',
            'PCR',
            'Radiographie',
            'Échographie',
            'Scanner',
            'IRM',
        ];

        return view('laboratory.create', compact('patients', 'consultations', 'analysisTypes'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create lab request')) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'analysis_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        LaboratoryAnalysis::create([
            'patient_id' => $validated['patient_id'],
            'consultation_id' => $validated['consultation_id'] ?? null,
            'requested_by' => Auth::id(),
            'analysis_type' => $validated['analysis_type'],
            'description' => $validated['description'] ?? null,
            'status' => 'demandee',
            'requested_at' => now(),
        ]);

        return redirect()->route('laboratory.index')
            ->with('success', 'Demande d\'analyse créée avec succès.');
    }

    public function show(LaboratoryAnalysis $laboratory)
    {
        if (!Auth::user()->can('view lab results')) {
            abort(403);
        }

        $laboratory->load(['patient', 'consultation', 'requestedBy', 'validatedBy']);

        return view('laboratory.show', compact('laboratory'));
    }

    public function edit(LaboratoryAnalysis $laboratory)
    {
        if (!Auth::user()->can('enter lab results')) {
            abort(403);
        }

        return view('laboratory.edit', compact('laboratory'));
    }

    public function update(Request $request, LaboratoryAnalysis $laboratory)
    {
        if (!Auth::user()->can('enter lab results')) {
            abort(403);
        }

        $validated = $request->validate([
            'results' => 'required|string',
        ]);

        $laboratory->update([
            'results' => $validated['results'],
            'status' => 'terminee',
            'completed_at' => now(),
        ]);

        return redirect()->route('laboratory.show', $laboratory)
            ->with('success', 'Résultats saisis avec succès.');
    }

    public function validateAnalysis(LaboratoryAnalysis $laboratory)
    {
        if (!Auth::user()->can('validate lab results')) {
            abort(403);
        }

        if ($laboratory->status !== 'terminee') {
            return back()->with('error', 'Les résultats doivent être saisis avant validation.');
        }

        $laboratory->update([
            'status' => 'validee',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        return redirect()->route('laboratory.show', $laboratory)
            ->with('success', 'Résultats validés avec succès.');
    }
}
