<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Hospitalization;
use App\Models\LaboratoryAnalysis;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_patients' => Patient::count(),
            'today_consultations' => Consultation::whereDate('date_consultation', today())->count(),
            'today_admissions' => Hospitalization::whereDate('admission_date', today())->count(),
            'hospitalized_patients' => Hospitalization::where('status', 'active')->count(),
            'today_appointments' => RendezVous::whereDate('date_heure', today())->count(),
            'today_prescriptions' => Consultation::whereDate('date_consultation', today())
                ->whereNotNull('ordonnance')
                ->where('ordonnance', '!=', '')
                ->count(),
            'pending_analyses' => LaboratoryAnalysis::whereIn('status', ['demandee', 'en_cours'])->count(),
            'low_stock_medications' => Medication::whereColumn('stock_quantity', '<=', 'min_stock_threshold')->count(),
        ];

        $recentConsultations = Consultation::with('patient')
            ->orderBy('date_consultation', 'desc')
            ->limit(5)
            ->get();

        $pendingLabAnalyses = LaboratoryAnalysis::with('patient')
            ->whereIn('status', ['demandee', 'en_cours'])
            ->orderBy('requested_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentConsultations', 'pendingLabAnalyses'));
    }
}
