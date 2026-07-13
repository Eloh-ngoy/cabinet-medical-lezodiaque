<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Médical Complet</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
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

        .patient-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .patient-photo {
            grid-column: span 2;
            text-align: center;
        }

        .patient-photo img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
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

        .medical-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
        }

        .consultation-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #3b82f6;
        }

        .consultation-header {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .consultation-date {
            color: #64748b;
            font-size: 14px;
        }

        .consultation-details {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
        }

        .consultation-details strong {
            color: #0f172a;
        }

        .prescription-item {
            background: #f0f9ff;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 3px solid #0ea5e9;
        }

        .analysis-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .analysis-table th,
        .analysis-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }

        .analysis-table th {
            background: #1e40af;
            color: white;
            font-weight: 600;
        }

        .analysis-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
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

        .qr-code {
            width: 80px;
            height: 80px;
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

        .signature-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            width: 200px;
            border-bottom: 2px solid #1e40af;
            margin-bottom: 10px;
        }

        .signature-text {
            font-size: 12px;
            color: #64748b;
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
            <p class="document-title">Dossier Médical Complet</p>
            <p class="document-id">Document ID: {{ $document->document_id }}</p>
        </div>

        <!-- Informations du Patient -->
        <div class="section">
            <h2 class="section-title">Informations Administratives</h2>
            <div class="patient-info">
                @if($patient->photo)
                <div class="patient-photo">
                    <img src="{{ asset($patient->photo) }}" alt="Photo du patient">
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Numéro Patient:</span>
                    <span class="info-value">{{ $patient->numero_unique }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nom:</span>
                    <span class="info-value">{{ $patient->nom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Prénom:</span>
                    <span class="info-value">{{ $patient->prenom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sexe:</span>
                    <span class="info-value">{{ $patient->sexe }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de Naissance:</span>
                    <span class="info-value">{{ $patient->date_naissance->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Âge:</span>
                    <span class="info-value">{{ $patient->date_naissance->age }} ans</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $patient->telephone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Adresse:</span>
                    <span class="info-value">{{ $patient->adresse }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Contact Urgence:</span>
                    <span class="info-value">{{ $patient->contact_urgence }}</span>
                </div>
            </div>
        </div>

        <!-- Informations Médicales -->
        <div class="section">
            <h2 class="section-title">Informations Médicales</h2>
            <div class="medical-info">
                <div class="patient-info">
                    <div class="info-row">
                        <span class="info-label">Groupe Sanguin:</span>
                        <span class="info-value">{{ $patient->groupe_sanguin ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Allergies:</span>
                        <span class="info-value">{{ $patient->allergies ?? 'Aucune connue' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Antécédents:</span>
                        <span class="info-value">{{ $patient->antecedents ?? 'Aucun' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Maladies Chroniques:</span>
                        <span class="info-value">{{ $patient->maladies_chroniques ?? 'Aucune' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des Consultations -->
        <div class="section">
            <h2 class="section-title">Historique des Consultations</h2>
            @forelse($patient->consultations->sortByDesc('date_consultation') as $consultation)
            <div class="consultation-item">
                <div class="consultation-header">
                    <span>Dr. {{ $consultation->user->full_name ?? 'Non renseigné' }}</span>
                    <span class="consultation-date">{{ $consultation->date_consultation->format('d/m/Y H:i') }}</span>
                </div>
                <div class="consultation-details">
                    <strong>Motif:</strong> {{ $consultation->motif }}<br>
                    @if($consultation->diagnostic)
                    <strong>Diagnostic:</strong> {{ $consultation->diagnostic }}<br>
                    @endif
                    @if($consultation->traitement)
                    <strong>Traitement:</strong> {{ $consultation->traitement }}<br>
                    @endif
                    @if($consultation->ordonnance)
                    <strong>Ordonnance:</strong> {{ $consultation->ordonnance }}<br>
                    @endif
                    <strong>Prix:</strong> {{ number_format($consultation->prix, 0) }} CDF
                </div>
            </div>
            @empty
            <p style="color: #64748b;">Aucune consultation enregistrée</p>
            @endforelse
        </div>

        <!-- Historique des Hospitalisations -->
        <div class="section">
            <h2 class="section-title">Historique des Hospitalisations</h2>
            @forelse($patient->hospitalizations->sortByDesc('date_entree') as $hospitalization)
            <div class="consultation-item" style="border-left-color: #10b981;">
                <div class="consultation-header">
                    <span>Service: {{ $hospitalization->service ?? 'Non renseigné' }}</span>
                    <span class="consultation-date">{{ $hospitalization->date_entree->format('d/m/Y') }} - {{ $hospitalization->date_sortie?->format('d/m/Y') ?? 'En cours' }}</span>
                </div>
                <div class="consultation-details">
                    <strong>Chambre:</strong> {{ $hospitalization->bed?->numero ?? 'Non renseigné' }}<br>
                    <strong>Observations:</strong> {{ $hospitalization->observations ?? 'Non renseigné' }}
                </div>
            </div>
            @empty
            <p style="color: #64748b;">Aucune hospitalisation enregistrée</p>
            @endforelse
        </div>

        <div class="footer">
            <div class="footer-content">
                <div>
                    <strong>MediNexus - Système d'Information Hospitalier</strong><br>
                    Date de génération: {{ $document->generated_at->format('d/m/Y H:i') }}<br>
                    Généré par: {{ $document->user->full_name }}
                </div>
                <div class="qr-section">
                    <div class="code-unique">
                        <div class="code-label">Code Unique:</div>
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
        .qr-section {
            text-align: center;
        }
        .code-unique {
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            border: 2px solid #e2e8f0;
        }
        .code-label {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .code-value {
            font-size: 14px;
            font-weight: 700;
            color: #1e40af;
            font-family: 'Courier New', monospace;
        }
    </style>
</body>
</html>