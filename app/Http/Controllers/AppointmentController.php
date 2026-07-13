<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('view appointments')) {
            abort(403);
        }

        $search = $request->get('search');
        $status = $request->get('status');
        $appointments = RendezVous::with('patient')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('numero_unique', 'like', "%{$search}%");
                })->orWhere('motif', 'like', "%{$search}%");
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('statut', $status);
            })
            ->orderBy('date_heure', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Patient $patient = null)
    {
        if (!Auth::user()->can('create appointment')) {
            abort(403);
        }

        $patients = Patient::all();

        return view('appointments.create', compact('patient', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('create appointment')) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string',
            'statut' => 'required|in:planifie,confirme,annule,termine',
        ]);

        RendezVous::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RendezVous $appointment)
    {
        if (!Auth::user()->can('view appointment details')) {
            abort(403);
        }

        $appointment->load('patient');

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RendezVous $appointment)
    {
        if (!Auth::user()->can('edit appointment')) {
            abort(403);
        }

        $patients = Patient::all();
        return view('appointments.edit', compact('appointment', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RendezVous $appointment)
    {
        if (!Auth::user()->can('edit appointment')) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string',
            'statut' => 'required|in:planifie,confirme,annule,termine',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RendezVous $appointment)
    {
        if (!Auth::user()->can('delete appointment')) {
            abort(403);
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous supprimé avec succès.');
    }
}