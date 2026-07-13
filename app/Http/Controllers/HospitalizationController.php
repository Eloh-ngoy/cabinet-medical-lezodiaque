<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Hospitalization;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalizationController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->can('view hospitalizations')) {
            abort(403);
        }

        $status = $request->get('status');
        $search = $request->get('search');
        $hospitalizations = Hospitalization::with(['patient', 'bed'])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('numero_unique', 'like', "%{$search}%");
                });
            })
            ->orderBy('admission_date', 'desc')
            ->paginate(10);

        return view('hospitalizations.index', compact('hospitalizations', 'status', 'search'));
    }

    public function create()
    {
        if (!Auth::user()->can('create hospitalization')) {
            abort(403);
        }

        $patients = Patient::orderBy('nom')->get();
        $beds = Bed::where('is_available', true)->orderBy('room_number')->orderBy('bed_number')->get();

        return view('hospitalizations.create', compact('patients', 'beds'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create hospitalization')) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_date' => 'required|date',
            'expected_duration' => 'nullable|integer|min:1',
            'admission_reason' => 'nullable|string',
        ]);

        $hospitalization = Hospitalization::create($validated);

        $bed = Bed::find($validated['bed_id']);
        $bed->update(['is_available' => false]);

        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalisation créée avec succès.');
    }

    public function show(Hospitalization $hospitalization)
    {
        if (!Auth::user()->can('view hospitalizations')) {
            abort(403);
        }

        $hospitalization->load(['patient', 'bed', 'posologies']);

        return view('hospitalizations.show', compact('hospitalization'));
    }

    public function edit(Hospitalization $hospitalization)
    {
        if (!Auth::user()->can('edit hospitalization')) {
            abort(403);
        }

        $patients = Patient::orderBy('nom')->get();
        $beds = Bed::orderBy('room_number')->orderBy('bed_number')->get();

        return view('hospitalizations.edit', compact('hospitalization', 'patients', 'beds'));
    }

    public function update(Request $request, Hospitalization $hospitalization)
    {
        if (!Auth::user()->can('edit hospitalization')) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_date' => 'required|date',
            'expected_duration' => 'nullable|integer|min:1',
            'admission_reason' => 'nullable|string',
        ]);

        if ($hospitalization->bed_id != $validated['bed_id']) {
            Bed::where('id', $hospitalization->bed_id)->update(['is_available' => true]);
            Bed::where('id', $validated['bed_id'])->update(['is_available' => false]);
        }

        $hospitalization->update($validated);

        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalisation mise à jour avec succès.');
    }

    public function destroy(Hospitalization $hospitalization)
    {
        if (!Auth::user()->can('edit hospitalization')) {
            abort(403);
        }

        if ($hospitalization->status === 'active') {
            Bed::where('id', $hospitalization->bed_id)->update(['is_available' => true]);
        }

        $hospitalization->delete();

        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalisation supprimée avec succès.');
    }

    public function discharge(Request $request, Hospitalization $hospitalization)
    {
        if (!Auth::user()->can('discharge patient')) {
            abort(403);
        }

        $validated = $request->validate([
            'discharge_date' => 'required|date',
            'discharge_notes' => 'nullable|string',
        ]);

        $hospitalization->update([
            'status' => 'discharged',
            'discharge_date' => $validated['discharge_date'],
            'discharge_notes' => $validated['discharge_notes'],
        ]);

        Bed::where('id', $hospitalization->bed_id)->update(['is_available' => true]);

        return redirect()->route('hospitalizations.show', $hospitalization)
            ->with('success', 'Patient sorti avec succès.');
    }
}
