<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DocumentService
{
    public function logDocumentCreation(array $data): Document
    {
        $document = Document::create([
            'type' => $data['type'],
            'patient_id' => $data['patient_id'] ?? null,
            'consultation_id' => $data['consultation_id'] ?? null,
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->roles->first()->name ?? 'unknown',
            'ip_address' => request()->ip(),
            'generated_at' => now(),
            'metadata' => $data['metadata'] ?? null,
        ]);

        $auditMessage = $document->getAuditDescription();

        activity()
            ->performedOn($document)
            ->causedBy(Auth::user())
            ->withProperties([
                'ip_address' => request()->ip(),
                'description' => $auditMessage,
            ])
            ->log('document_generated');

        return $document;
    }
}
