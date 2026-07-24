<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Consultation</title>
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
            <p class="document-title">Rapport de Consultation</p>
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
            <h2 class="section-title">Consultation</h2>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $consultation->date_consultation->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Médecin:</span>
                    <span class="info-value">Dr. {{ $consultation->user->full_name ?? 'Non renseigné' }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Motif de Consultation</h2>
            <div class="content-box">
                {{ $consultation->motif }}
            </div>
        </div>

        @if($consultation->diagnostic)
        <div class="section">
            <h2 class="section-title">Diagnostic</h2>
            <div class="content-box">
                {{ $consultation->diagnostic }}
            </div>
        </div>
        @endif

        @if($consultation->traitement)
        <div class="section">
            <h2 class="section-title">Traitement Prescrit</h2>
            <div class="content-box">
                {{ $consultation->traitement }}
            </div>
        </div>
        @endif

        @if($consultation->ordonnance)
        <div class="section">
            <h2 class="section-title">Ordonnance</h2>
            <div class="content-box" style="white-space: pre-line;">
                {{ $consultation->ordonnance }}
            </div>
        </div>
        @endif

        <div class="section">
            <h2 class="section-title">Coût de la Consultation</h2>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Montant:</span>
                    <span class="info-value">{{ number_format($consultation->prix, 0) }} CDF</span>
                </div>
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