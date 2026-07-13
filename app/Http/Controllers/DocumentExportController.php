<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Patient;
use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentExportController extends Controller
{

    /**
     * Journalise la génération d'un document
     */
    protected function logDocumentCreation(array $data): Document
    {
        $document = Document::create([
            'type' => $data['type'],
            'patient_id' => $data['patient_id'] ?? null,
            'consultation_id' => $data['consultation_id'] ?? null,
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->roles->first()->name ?? 'unknown',
            'ip_address' => request()->ip(),
            'generated_at' => now(),
            'metadata' => $data['metadata'] ?? null
        ]);

        // Générer le message d'audit
        $auditMessage = $document->getAuditDescription();

        // Logger dans activitylog
        activity()
            ->performedOn($document)
            ->causedBy(Auth::user())
            ->withProperties([
                'ip_address' => request()->ip(),
                'description' => $auditMessage
            ])
            ->log('document_generated');

        return $document;
    }

    /**
     * Vérifie les permissions d'export selon le rôle
     */
    protected function checkExportPermission(string $type): bool
    {
        $user = Auth::user();
        $role = $user->roles->first()->name ?? null;

        return match ($type) {
            'dossier_medical', 'resume_medical', 'rapport_consultation', 'rapport_hospitalisation' =>
                in_array($role, ['Directeur Général Médecin', 'Médecin']),
            'ordonnance', 'historique_prescriptions' =>
                in_array($role, ['Directeur Général Médecin', 'Médecin', 'Pharmacien']),
            'rapport_laboratoire' =>
                in_array($role, ['Directeur Général Médecin', 'Médecin', 'Laborantin']),
            'audit_patient' =>
                $role === 'Directeur Général Médecin',
            default => false
        };
    }

    /**
     * Génère le dossier médical complet d'un patient
     */
    public function exportPatientMedicalRecord(Patient $patient)
    {
        if (!$this->checkExportPermission('dossier_medical')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            DB::beginTransaction();

            $document = $this->logDocumentCreation([
                'type' => 'dossier_medical',
                'patient_id' => $patient->id,
                'metadata' => [
                    'patient_name' => $patient->nom . ' ' . $patient->prenom,
                    'patient_number' => $patient->numero_unique
                ]
            ]);

            // Charger les relations nécessaires pour éviter N+1 queries
            $patient->load(['consultations.user', 'hospitalizations.bed']);

            $pdf = PDF::loadView('pdf.medical_record', [
                'patient' => $patient,
                'document' => $document,
                'watermark' => 'CONFIDENTIEL'
            ]);

            DB::commit();

            return $pdf->download("Dossier_Medical_{$patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Génère le résumé médical d'un patient
     */
    public function exportPatientSummary(Patient $patient)
    {
        if (!$this->checkExportPermission('resume_medical')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            DB::beginTransaction();

            $document = $this->logDocumentCreation([
                'type' => 'resume_medical',
                'patient_id' => $patient->id,
                'metadata' => [
                    'patient_name' => $patient->nom . ' ' . $patient->prenom,
                    'patient_number' => $patient->numero_unique
                ]
            ]);

            // Charger les relations nécessaires
            $patient->load(['consultations.user']);
            $latestConsultation = $patient->consultations->sortByDesc('date_consultation')->first();

            $pdf = PDF::loadView('pdf.medical_summary', [
                'patient' => $patient,
                'document' => $document,
                'latestConsultation' => $latestConsultation,
                'watermark' => 'COPIE PATIENT'
            ]);

            DB::commit();

            return $pdf->download("Resume_Medical_{$patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Génère une ordonnance PDF
     */
    public function exportPrescription(Consultation $consultation)
    {
        if (!$this->checkExportPermission('ordonnance')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            DB::beginTransaction();

            $document = $this->logDocumentCreation([
                'type' => 'ordonnance',
                'patient_id' => $consultation->patient_id,
                'consultation_id' => $consultation->id,
                'metadata' => [
                    'consultation_date' => $consultation->date_consultation->format('d/m/Y')
                ]
            ]);

            // Charger les relations nécessaires
            $consultation->load(['patient', 'user']);

            $pdf = PDF::loadView('pdf.prescription', [
                'consultation' => $consultation,
                'patient' => $consultation->patient,
                'document' => $document,
                'doctor' => Auth::user(),
                'watermark' => null
            ]);

            DB::commit();

            return $pdf->download("Ordonnance_{$consultation->patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Génère un rapport de consultation
     */
    public function exportConsultationReport(Consultation $consultation)
    {
        if (!$this->checkExportPermission('rapport_consultation')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            DB::beginTransaction();

            $document = $this->logDocumentCreation([
                'type' => 'rapport_consultation',
                'patient_id' => $consultation->patient_id,
                'consultation_id' => $consultation->id,
                'metadata' => [
                    'consultation_date' => $consultation->date_consultation->format('d/m/Y')
                ]
            ]);

            // Charger les relations nécessaires
            $consultation->load(['patient', 'user']);

            $pdf = PDF::loadView('pdf.consultation_report', [
                'consultation' => $consultation,
                'patient' => $consultation->patient,
                'document' => $document,
                'watermark' => 'CONFIDENTIEL'
            ]);

            DB::commit();

            return $pdf->download("Rapport_Consultation_{$consultation->patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Génère un rapport d'hospitalisation
     */
    public function exportHospitalizationReport($hospitalizationId)
    {
        if (!$this->checkExportPermission('rapport_hospitalisation')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            $hospitalization = \App\Models\Hospitalization::with('patient', 'bed')->findOrFail($hospitalizationId);

            DB::beginTransaction();

            $document = $this->logDocumentCreation([
                'type' => 'rapport_hospitalisation',
                'patient_id' => $hospitalization->patient_id,
                'metadata' => [
                    'hospitalization_id' => $hospitalizationId,
                    'admission_date' => $hospitalization->admission_date->format('d/m/Y')
                ]
            ]);

            $pdf = PDF::loadView('pdf.hospitalization_report', [
                'hospitalization' => $hospitalization,
                'patient' => $hospitalization->patient,
                'document' => $document,
                'watermark' => 'CONFIDENTIEL'
            ]);

            DB::commit();

            return $pdf->download("Rapport_Hospitalisation_{$hospitalization->patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Génère un rapport de laboratoire
     */
    public function exportLaboratoryReport($analysisId)
    {
        if (!$this->checkExportPermission('rapport_laboratoire')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            $analysisClass = '\\App\\Models\\LaboratoryAnalysis';
            if (!class_exists($analysisClass)) {
                return back()->with('error', 'Le module laboratoire n\'est pas encore disponible.');
            }

            $analysis = $analysisClass::with(['patient', 'requestedBy'])->findOrFail($analysisId);

            DB::beginTransaction();

            $document = $this->logDocumentCreation([
                'type' => 'rapport_laboratoire',
                'patient_id' => $analysis->patient_id,
                'metadata' => [
                    'analysis_id' => $analysisId
                ]
            ]);

            $pdf = PDF::loadView('pdf.laboratory_report', [
                'analysis' => $analysis,
                'patient' => $analysis->patient,
                'document' => $document,
                'watermark' => 'CONFIDENTIEL'
            ]);

            DB::commit();

            return $pdf->download("Rapport_Laboratoire_{$analysis->patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Génère l'historique des prescriptions
     */
    public function exportPrescriptionHistory(Patient $patient, Request $request)
    {
        if (!$this->checkExportPermission('historique_prescriptions')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            DB::beginTransaction();

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $doctorId = $request->input('doctor_id');

            $query = $patient->consultations()->with('user');

            if ($startDate) {
                $query->where('date_consultation', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('date_consultation', '<=', $endDate);
            }
            if ($doctorId) {
                $query->where('user_id', $doctorId);
            }

            $consultations = $query->get();

            $document = $this->logDocumentCreation([
                'type' => 'historique_prescriptions',
                'patient_id' => $patient->id,
                'metadata' => [
                    'filters' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'doctor_id' => $doctorId
                    ],
                    'count' => $consultations->count()
                ]
            ]);

            $pdf = PDF::loadView('pdf.prescription_history', [
                'patient' => $patient,
                'consultations' => $consultations->sortByDesc('date_consultation'),
                'document' => $document,
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'doctor_id' => $doctorId
                ],
                'watermark' => 'CONFIDENTIEL'
            ]);

            DB::commit();

            return $pdf->download("Historique_Prescriptions_{$patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Génère le rapport d'audit du patient
     */
    public function exportPatientAudit(Patient $patient)
    {
        if (!$this->checkExportPermission('audit_patient')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter ce type de document');
        }

        try {
            DB::beginTransaction();

            $activities = activity()
                ->where('subject_type', Patient::class)
                ->where('subject_id', $patient->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $document = $this->logDocumentCreation([
                'type' => 'audit_patient',
                'patient_id' => $patient->id,
                'metadata' => [
                    'patient_name' => $patient->nom . ' ' . $patient->prenom,
                    'activity_count' => $activities->count()
                ]
            ]);

            $pdf = PDF::loadView('pdf.patient_audit', [
                'patient' => $patient,
                'activities' => $activities,
                'document' => $document,
                'watermark' => 'CONFIDENTIEL'
            ]);

            DB::commit();

            return $pdf->download("Audit_Patient_{$patient->numero_unique}_{$document->document_id}.pdf");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Affiche l'interface d'export
     */
    public function showExportInterface(Patient $patient)
    {
        if (!Auth::user()->can('export medical record') && !Auth::user()->can('export medical summary')) {
            abort(403);
        }

        return view('patients.export', compact('patient'));
    }

    /**
     * Vérifie l'authenticité d'un document via QR Code
     */
    public function verifyDocument($documentId)
    {
        $document = Document::where('document_id', $documentId)->firstOrFail();

        return view('pdf.verification', [
            'document' => $document,
            'valid' => $document->status === 'valid'
        ]);
    }
}