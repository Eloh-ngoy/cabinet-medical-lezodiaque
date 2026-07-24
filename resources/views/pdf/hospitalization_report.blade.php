<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport d'Hospitalisation</title>
    <style>
        body {
            font-family: sans-serif;
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

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e40af;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-label {
            font-weight: 500;
            color: #64748b;
        }

        .info-value {
            font-weight: 600;
            color: #0f172a;
        }

        .content-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
            line-height: 1.8;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #1e40af;
            text-align: center;
            font-size: 11px;
            color: #64748b;
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
            <p class="document-title">Rapport d'Hospitalisation</p>
            <p class="document-id" style="color: #64748b; margin: 5px 0 0 0;">Document ID: {{ $document->document_id }}</p>
        </div>

        <div class="section">
            <h2 class="section-title">Patient</h2>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Nom:</span>
                    <span class="info-value">{{ $patient->nom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Prénom:</span>
                    <span class="info-value">{{ $patient->prenom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Numéro Patient:</span>
                    <span class="info-value">{{ $patient->numero_unique }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de Naissance:</span>
                    <span class="info-value">{{ $patient->date_naissance->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Détails de l'Hospitalisation</h2>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Date d'Entrée:</span>
                    <span class="info-value">{{ $hospitalization->admission_date->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de Sortie:</span>
                    <span class="info-value">{{ $hospitalization->discharge_date ? $hospitalization->discharge_date->format('d/m/Y H:i') : 'En cours' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut:</span>
                    <span class="info-value">{{ $hospitalization->status === 'active' ? 'En cours' : 'Sorti(e)' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Chambre:</span>
                    <span class="info-value">{{ $hospitalization->bed?->room_number ?? 'Non renseigné' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lit:</span>
                    <span class="info-value">{{ $hospitalization->bed?->bed_number ?? 'Non renseigné' }}</span>
                </div>
                @if($hospitalization->expected_duration)
                <div class="info-row">
                    <span class="info-label">Durée Prévue:</span>
                    <span class="info-value">{{ $hospitalization->expected_duration }} jours</span>
                </div>
                @endif
                @if($hospitalization->discharge_date)
                <div class="info-row">
                    <span class="info-label">Durée:</span>
                    <span class="info-value">{{ $hospitalization->admission_date->diffInDays($hospitalization->discharge_date) }} jours</span>
                </div>
                @endif
            </div>
        </div>

        @if($hospitalization->admission_reason)
        <div class="section">
            <h2 class="section-title">Motif d'admission</h2>
            <div class="content-box" style="white-space: pre-line;">
                {{ $hospitalization->admission_reason }}
            </div>
        </div>
        @endif

        @if($hospitalization->discharge_notes)
        <div class="section">
            <h2 class="section-title">Notes de sortie</h2>
            <div class="content-box" style="white-space: pre-line;">
                {{ $hospitalization->discharge_notes }}
            </div>
        </div>
        @endif

        <div class="section">
            <h2 class="section-title">Résumé</h2>
            <div class="content-box">
                <p><strong>Patient:</strong> {{ $patient->nom }} {{ $patient->prenom }}</p>
                <p><strong>Hospitalisé le:</strong> {{ $hospitalization->admission_date->format('d/m/Y') }}</p>
                @if($hospitalization->discharge_date)
                <p><strong>Sorti le:</strong> {{ $hospitalization->discharge_date->format('d/m/Y') }}</p>
                @endif
                <p><strong>Motif d'admission:</strong> {{ $hospitalization->admission_reason ?? 'Non renseigné' }}</p>
                <p><strong>Chambre:</strong> {{ $hospitalization->bed?->room_number ?? 'Non renseigné' }}</p>
            </div>
        </div>

        <div class="footer">
            <div>
                <strong>MediNexus - Système d'Information Hospitalier</strong><br>
                Date de génération: {{ $document->generated_at->format('d/m/Y H:i') }}<br>
                Document ID: {{ $document->document_id }}
            </div>
            <div class="code-unique-section">
                <div class="code-unique">
                    <div class="code-label">CODE UNIQUE</div>
                    <div class="code-value">{{ $document->document_id }}</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .code-unique-section {
            margin-top: 10px;
            text-align: right;
        }
        .code-unique {
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 4px;
            border: 2px solid #e2e8f0;
            display: inline-block;
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
