<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('view consultations')) {
            abort(403);
        }

        $search = $request->get('search');
        $consultations = Consultation::with(['patient', 'user'])
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('numero_unique', 'like', "%{$search}%");
                })->orWhere('motif', 'like', "%{$search}%")
                  ->orWhere('diagnostic', 'like', "%{$search}%");
            })
            ->orderBy('date_consultation', 'desc')
            ->paginate(10);

        return view('consultations.index', compact('consultations', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Patient $patient = null)
    {
        if (!Auth::user()->can('create consultation')) {
            abort(403);
        }

        $patients = Patient::all();

        return view('consultations.create', compact('patient', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('create consultation')) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string',
            'diagnostic' => 'nullable|string',
            'traitement' => 'nullable|string',
            'ordonnance' => 'nullable|string',
            'prix' => 'required|numeric',
        ]);

        Consultation::create($validated + ['user_id' => Auth::id()]);

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        if (!Auth::user()->can('view consultation details')) {
            abort(403);
        }

        $consultation->load(['patient', 'user']);

        return view('consultations.show', compact('consultation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consultation $consultation)
    {
        if (!Auth::user()->can('edit consultation')) {
            abort(403);
        }

        $patients = Patient::all();
        return view('consultations.edit', compact('consultation', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultation $consultation)
    {
        if (!Auth::user()->can('edit consultation')) {
            abort(403);
        }

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

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation)
    {
        if (!Auth::user()->can('delete consultation')) {
            abort(403);
        }

        $consultation->delete();

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation supprimée avec succès.');
    }
}
