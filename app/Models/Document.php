<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends BaseModel
{
    protected $fillable = [
        'document_id',
        'type',
        'patient_id',
        'consultation_id',
        'user_id',
        'user_role',
        'ip_address',
        'generated_at',
        'file_path',
        'status',
        'metadata'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'metadata' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->document_id)) {
                $document->document_id = self::generateDocumentId();
            }
            if (empty($document->generated_at)) {
                $document->generated_at = now();
            }
        });
    }

    public static function generateDocumentId(): string
    {
        $year = now()->format('Y');
        $latest = self::whereYear('created_at', $year)->latest()->first();
        $sequence = $latest ? (int) substr($latest->document_id, -6) + 1 : 1;
        return sprintf('DOC-%s-%06d', $year, $sequence);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAuditDescription(): string
    {
        $user = $this->user;
        $patient = $this->patient;
        $type = match($this->type) {
            'dossier_medical' => 'dossier médical complet',
            'resume_medical' => 'résumé médical',
            'ordonnance' => 'ordonnance',
            'rapport_consultation' => 'rapport de consultation',
            'rapport_hospitalisation' => 'rapport d\'hospitalisation',
            'rapport_laboratoire' => 'rapport de laboratoire',
            'historique_prescriptions' => 'historique des prescriptions',
            'audit_patient' => 'rapport d\'audit du patient',
            default => $this->type
        };

        $userName = $user ? $user->full_name : 'Utilisateur inconnu';
        $patientName = $patient ? "{$patient->nom} {$patient->prenom}" : 'Patient inconnu';
        $patientNumber = $patient ? $patient->numero_unique : 'N/A';
        $date = $this->generated_at->format('d/m/Y à H:i');

        return "{$userName} a généré le {$type} du patient {$patientName} ({$patientNumber}) le {$date}.";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'status', 'generated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
