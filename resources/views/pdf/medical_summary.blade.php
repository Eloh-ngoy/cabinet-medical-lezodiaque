<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé Médical</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 20mm;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: #1e40af;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .logo svg {
            width: 30px;
            height: 30px;
            fill: white;
        }

        .establishment-name {
            font-size: 24px;
            font-weight: 700;
            color: #1e40af;
            margin: 0;
        }

        .document-title {
            font-size: 18px;
            font-weight: 600;
            color: #475569;
            margin: 5px 0 0 0;
        }

        .document-id {
            font-size: 12px;
            color: #64748b;
            margin: 5px 0 0 0;
        }

        .important-info {
            background: #fee2e2;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
            margin-bottom: 25px;
        }

        .important-info-title {
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-label {
            font-weight: 500;
            color: #64748b;
            font-size: 13px;
        }

        .info-value {
            font-weight: 600;
            color: #0f172a;
            font-size: 13px;
        }

        .info-value.alert {
            color: #dc2626;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e40af;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .summary-box {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
        }

        .summary-item {
            margin-bottom: 8px;
            font-size: 13px;
            line-height: 1.5;
        }

        .summary-item strong {
            color: #0f172a;
        }

        .treatment-current {
            background: #ecfdf5;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #10b981;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #1e40af;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-info {
            font-size: 10px;
            color: #94a3b8;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            font-weight: 700;
            color: rgba(30, 64, 175, 0.05);
            pointer-events: none;
            white-space: nowrap;
        }

        @media print {
            .container {
                width: 100%;
                padding: 15mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @if($watermark)
        <div class="watermark">{{ $watermark }}</div>
        @endif

        <div class="header">
            <div class="logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h1 class="establishment-name">MediNexus</h1>
            <p class="document-title">Résumé Médical</p>
            <p class="document-id">Document ID: {{ $document->document_id }}</p>
        </div>

        <!-- Informations Essentielles -->
        <div class="section">
            <h2 class="section-title">Identité du Patient</h2>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Numéro Patient:</span>
                    <span class="info-value">{{ $patient->numero_unique }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nom Complet:</span>
                    <span class="info-value">{{ $patient->nom }} {{ $patient->prenom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de Naissance:</span>
                    <span class="info-value">{{ $patient->date_naissance->format('d/m/Y') }} ({{ $patient->date_naissance->age }} ans)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Groupe Sanguin:</span>
                    <span class="info-value">{{ $patient->groupe_sanguin ?? 'Non renseigné' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $patient->telephone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Contact Urgence:</span>
                    <span class="info-value">
                        {{ $patient->contact_urgence_nom ?? 'Non renseigné' }}
                        @if($patient->contact_urgence_telephone)
                            ({{ $patient->contact_urgence_telephone }})
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Informations Critiques -->
        @if($patient->allergies || $patient->maladies_chroniques)
        <div class="important-info">
            <div class="important-info-title">⚠️ INFORMATIONS CRITIQUES</div>
            <div class="info-grid">
                @if($patient->allergies)
                <div class="info-row">
                    <span class="info-label">Allergies:</span>
                    <span class="info-value alert">{{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : $patient->allergies }}</span>
                </div>
                @endif
                @if($patient->maladies_chroniques)
                <div class="info-row">
                    <span class="info-label">Maladies Chroniques:</span>
                    <span class="info-value alert">{{ is_array($patient->maladies_chroniques) ? implode(', ', $patient->maladies_chroniques) : $patient->maladies_chroniques }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Dernier Diagnostic -->
        @if($latestConsultation)
        <div class="section">
            <h2 class="section-title">Dernière Consultation</h2>
            <div class="summary-box">
                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{ $latestConsultation->date_consultation->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Médecin:</span>
                        <span class="info-value">Dr. {{ $latestConsultation->user->full_name ?? 'Non renseigné' }}</span>
                    </div>
                </div>
                @if($latestConsultation->diagnostic)
                <div class="summary-item" style="margin-top: 10px;">
                    <strong>Diagnostic:</strong> {{ $latestConsultation->diagnostic }}
                </div>
                @endif
                @if($latestConsultation->traitement)
                <div class="summary-item">
                    <strong>Traitement:</strong> {{ $latestConsultation->traitement }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Traitement en Cours -->
        @if($latestConsultation && $latestConsultation->ordonnance)
        <div class="section">
            <h2 class="section-title">Traitement en Cours</h2>
            <div class="treatment-current">
                <div class="summary-item">
                    {{ $latestConsultation->ordonnance }}
                </div>
            </div>
        </div>
        @endif

        <!-- Antécédents -->
        @if($patient->antecedents)
        <div class="section">
            <h2 class="section-title">Antécédents Médicaux</h2>
            <div class="summary-item">
                {{ is_array($patient->antecedents) ? implode(', ', $patient->antecedents) : $patient->antecedents }}
            </div>
        </div>
        @endif

        <div class="footer">
            <div class="footer-content">
                <div>
                    <strong>MediNexus - Système d'Information Hospitalier</strong><br>
                    Date de génération: {{ $document->generated_at->format('d/m/Y H:i') }}<br>
                    Généré par: {{ $document->user->full_name }}<br>
                    <em>Ce document ne contient que les informations essentielles. Pour le dossier complet, consultez le dossier médical.</em>
                </div>
                <div class="code-unique-section">
                    <div class="code-unique">
                        <div class="code-label">CODE UNIQUE</div>
                        <div class="code-value">{{ $document->document_id }}</div>
                    </div>
                </div>
            </div>
            <div class="page-info">
                Page 1/1 - Document officiel MediNexus
            </div>
        </div>
    </div>

    <style>
        .code-unique-section {
            margin-top: 10px;
        }
        .code-unique {
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 4px;
            border: 2px solid #e2e8f0;
        }
        .code-label {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 2px;
        }
        .code-value {
            font-size: 12px;
            font-weight: 700;
            color: #1e40af;
            font-family: 'Courier New', monospace;
        }
    </style>
</body>
</html>