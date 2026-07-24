<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordonnance Médicale</title>
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
            padding: 25mm 20mm;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: #1e40af;
            border-radius: 12px;
            margin-right: 20px;
        }

        .logo svg {
            width: 35px;
            height: 35px;
            fill: white;
        }

        .establishment-info {
            text-align: left;
        }

        .establishment-name {
            font-size: 28px;
            font-weight: 700;
            color: #1e40af;
            margin: 0;
        }

        .establishment-address {
            font-size: 12px;
            color: #64748b;
            margin: 5px 0 0 0;
        }

        .establishment-phone {
            font-size: 12px;
            color: #64748b;
            margin: 3px 0 0 0;
        }

        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #1e40af;
            margin: 25px 0 30px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e40af;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .patient-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #1e40af;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
        }

        .info-value {
            font-weight: 700;
            color: #0f172a;
            font-size: 14px;
        }

        .prescription-content {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            border: 2px solid #1e40af;
            min-height: 200px;
            margin-bottom: 30px;
        }

        .prescription-text {
            white-space: pre-line;
            font-size: 15px;
            line-height: 2;
            color: #0f172a;
        }

        .warning-box {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 20px;
            font-size: 13px;
            color: #92400e;
        }

        .signature-section {
            margin-top: 40px;
            padding: 25px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .signature-header {
            text-align: right;
            margin-bottom: 20px;
        }

        .doctor-info {
            text-align: right;
            font-size: 14px;
            color: #0f172a;
            line-height: 1.6;
        }

        .signature-line {
            display: inline-block;
            width: 250px;
            border-bottom: 2px solid #1e40af;
            margin-bottom: 15px;
        }

        .signature-text {
            font-size: 12px;
            color: #64748b;
        }

        .date-section {
            text-align: right;
            margin-top: 15px;
            font-size: 13px;
            color: #64748b;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #1e40af;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-info {
            font-size: 11px;
            color: #64748b;
            line-height: 1.6;
        }

        .qr-section {
            text-align: center;
        }

        .code-unique {
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            border: 2px solid #1e40af;
            margin-bottom: 5px;
        }

        .code-label {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .code-value {
            font-size: 13px;
            font-weight: 700;
            color: #1e40af;
            font-family: 'Courier New', monospace;
        }

        .qr-text {
            font-size: 9px;
            color: #64748b;
        }

        .document-id {
            position: absolute;
            top: 25mm;
            right: 20mm;
            font-size: 11px;
            color: #64748b;
        }

        .valid-stamp {
            position: absolute;
            top: 50%;
            right: 30mm;
            transform: translateY(-50%) rotate(-15deg);
            border: 3px solid #10b981;
            padding: 10px 20px;
            border-radius: 8px;
            color: #10b981;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
        }

        @media print {
            .container {
                width: 100%;
                padding: 20mm 15mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="document-id">ID: {{ $document->document_id }}</div>

        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="establishment-info">
                    <h1 class="establishment-name">MediNexus</h1>
                    <p class="establishment-address">Kinshasa, République Démocratique du Congo</p>
                    <p class="establishment-phone">Tél: +243 XXX XXX XXX</p>
                </div>
            </div>
        </div>

        <div class="document-title">Ordonnance Médicale</div>

        <div class="section">
            <h2 class="section-title">Patient</h2>
            <div class="patient-info">
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
                        <span class="info-label">Date de Naissance:</span>
                        <span class="info-value">{{ $patient->date_naissance->format('d/m/Y') }} ({{ $patient->date_naissance->age }} ans)</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Numéro Dossier:</span>
                        <span class="info-value">{{ $patient->numero_unique }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Téléphone:</span>
                        <span class="info-value">{{ $patient->telephone }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Sexe:</span>
                        <span class="info-value">{{ $patient->sexe }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Prescription</h2>
            <div class="warning-box">
                <strong>⚠️ IMPORTANT:</strong> Cette ordonnance est valable uniquement après validation par le médecin prescripteur. Ne jamais modifier les dosages sans avis médical.
            </div>
            <div class="prescription-content">
                @if($consultation->ordonnance)
                <div class="prescription-text">{{ $consultation->ordonnance }}</div>
                @else
                <p style="color: #94a3b8; text-align: center; padding: 40px;">Aucune prescription renseignée</p>
                @endif
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-header">
                <div class="doctor-info">
                    <strong>Dr. {{ $doctor->full_name }}</strong><br>
                    Médecin Généraliste<br>
                    Matricule: {{ $doctor->id }}<br>
                </div>
                <div class="signature-line"></div>
                <div class="signature-text">Signature électronique</div>
            </div>
            <div class="date-section">
                Date de prescription: {{ $consultation->date_consultation->format('d/m/Y') }}<br>
                Date de validation: {{ $document->generated_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="footer">
            <div class="footer-info">
                <strong>MediNexus - Système d'Information Hospitalier</strong><br>
                Ordonnance officielle générée électroniquement<br>
                Date de génération: {{ $document->generated_at->format('d/m/Y H:i') }}<br>
                Document ID: {{ $document->document_id }}
            </div>
            <div class="qr-section">
                <div class="code-unique">
                    <div class="code-label">CODE UNIQUE</div>
                    <div class="code-value">{{ $document->document_id }}</div>
                </div>
                <div class="qr-text">Vérification: {{ route('documents.verify', $document->document_id) }}</div>
            </div>
        </div>
    </div>
</body>
</html>