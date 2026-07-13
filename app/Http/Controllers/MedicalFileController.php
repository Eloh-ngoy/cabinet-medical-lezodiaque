<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalFileController extends Controller
{
    public function show(Patient $patient)
    {
        if (!Auth::user()->can('view patients')) {
            abort(403);
        }

        $patient->load([
            'consultations' => fn ($q) => $q->orderBy('date_consultation', 'desc'),
            'consultations.user',
            'hospitalizations' => fn ($q) => $q->orderBy('admission_date', 'desc'),
            'hospitalizations.bed',
            'hospitalizations.posologies',
            'rendezVous' => fn ($q) => $q->orderBy('date_heure', 'desc'),
            'laboratoryAnalyses' => fn ($q) => $q->orderBy('requested_at', 'desc'),
            'laboratoryAnalyses.requestedBy',
            'laboratoryAnalyses.validatedBy',
        ]);

        return view('medical_files.show', compact('patient'));
    }
}
