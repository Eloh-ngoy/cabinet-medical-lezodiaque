@extends('layouts.app')

@section('title', 'Dossier Médical')

@section('content')
    <div class="mb-8">
        <a href="{{ route('patients.show', $patient) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Retour au patient
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Dossier Médical Complet</h1>
        <p class="text-gray-600 mt-1">{{ $patient->nom }} {{ $patient->prenom }} — {{ $patient->numero_unique }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $patient->consultations->count() }}</p>
            <p class="text-sm text-gray-600">Consultations</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-orange-600">{{ $patient->hospitalizations->count() }}</p>
            <p class="text-sm text-gray-600">Hospitalisations</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $patient->laboratoryAnalyses->count() }}</p>
            <p class="text-sm text-gray-600">Analyses</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $patient->rendezVous->count() }}</p>
            <p class="text-sm text-gray-600">Rendez-vous</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations administratives</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div><p class="text-sm text-gray-600">Date de naissance</p><p class="text-gray-900">{{ $patient->date_naissance->format('d/m/Y') }}</p></div>
                <div><p class="text-sm text-gray-600">Sexe</p><p class="text-gray-900">{{ ucfirst($patient->sexe) }}</p></div>
                <div><p class="text-sm text-gray-600">Groupe sanguin</p><p class="text-gray-900">{{ $patient->groupe_sanguin ?? 'Non renseigné' }}</p></div>
                <div><p class="text-sm text-gray-600">Téléphone</p><p class="text-gray-900">{{ $patient->telephone }}</p></div>
                <div><p class="text-sm text-gray-600">Adresse</p><p class="text-gray-900">{{ $patient->adresse ?? 'N/A' }}</p></div>
                <div><p class="text-sm text-gray-600">Contact urgence</p><p class="text-gray-900">{{ $patient->contact_urgence_nom ?? 'N/A' }} ({{ $patient->contact_urgence_telephone ?? 'N/A' }})</p></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations médicales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><p class="text-sm text-gray-600">Allergies</p><p class="text-gray-900">{{ $patient->allergies ? implode(', ', $patient->allergies) : 'Aucune' }}</p></div>
                <div><p class="text-sm text-gray-600">Antécédents</p><p class="text-gray-900">{{ $patient->antecedents ? implode(', ', $patient->antecedents) : 'Aucun' }}</p></div>
                <div><p class="text-sm text-gray-600">Maladies chroniques</p><p class="text-gray-900">{{ $patient->maladies_chroniques ? implode(', ', $patient->maladies_chroniques) : 'Aucune' }}</p></div>
                <div><p class="text-sm text-gray-600">Traitement passé</p><p class="text-gray-900">{{ $patient->traitement_passe ?? 'Aucun' }}</p></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Consultations ({{ $patient->consultations->count() }})</h3>
            @if($patient->consultations->count() > 0)
                <div class="space-y-3">
                    @foreach($patient->consultations as $consultation)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-600">{{ $consultation->date_consultation->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $consultation->motif }}</p>
                                    @if($consultation->diagnostic)<p class="text-sm text-gray-700 mt-1"><strong>Diagnostic:</strong> {{ $consultation->diagnostic }}</p>@endif
                                    @if($consultation->traitement)<p class="text-sm text-gray-700"><strong>Traitement:</strong> {{ $consultation->traitement }}</p>@endif
                                    @if($consultation->ordonnance)<p class="text-sm text-gray-700"><strong>Ordonnance:</strong> {{ $consultation->ordonnance }}</p>@endif
                                    @if($consultation->user)<p class="text-sm text-gray-500 mt-1">Médecin: {{ $consultation->user->full_name }}</p>@endif
                                </div>
                                <a href="{{ route('consultations.show', $consultation) }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucune consultation</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hospitalisations ({{ $patient->hospitalizations->count() }})</h3>
            @if($patient->hospitalizations->count() > 0)
                <div class="space-y-3">
                    @foreach($patient->hospitalizations as $hospitalization)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-600">Admission: {{ $hospitalization->admission_date->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-gray-700 mt-1">Lit: {{ $hospitalization->bed?->label() ?? 'N/A' }}</p>
                                    @if($hospitalization->admission_reason)<p class="text-sm text-gray-700">Motif: {{ $hospitalization->admission_reason }}</p>@endif
                                    @if($hospitalization->discharge_date)<p class="text-sm text-gray-500">Sortie: {{ $hospitalization->discharge_date->format('d/m/Y H:i') }}</p>@endif
                                    @if($hospitalization->posologies->count() > 0)
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500 font-medium">Posologies:</p>
                                            @foreach($hospitalization->posologies as $posology)
                                                <p class="text-xs text-gray-600">• {{ $posology->medication_name }} — {{ $posology->dosage }}, {{ $posology->frequency }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('hospitalizations.show', $hospitalization) }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucune hospitalisation</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Analyses de laboratoire ({{ $patient->laboratoryAnalyses->count() }})</h3>
            @if($patient->laboratoryAnalyses->count() > 0)
                <div class="space-y-3">
                    @foreach($patient->laboratoryAnalyses as $analysis)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $analysis->analysis_type }}</p>
                                    <p class="text-sm text-gray-600">Demandée le: {{ $analysis->requested_at->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-gray-600">Par: {{ $analysis->requestedBy?->full_name ?? 'N/A' }}</p>
                                    @if($analysis->results)<p class="text-sm text-gray-700 mt-1">Résultats: {{ Str::limit($analysis->results, 100) }}</p>@endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @php $statusLabels = ['demandee' => 'Demandée', 'en_cours' => 'En cours', 'terminee' => 'Terminée', 'validee' => 'Validée']; @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $statusLabels[$analysis->status] ?? $analysis->status }}</span>
                                    <a href="{{ route('laboratory.show', $analysis) }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucune analyse</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rendez-vous ({{ $patient->rendezVous->count() }})</h3>
            @if($patient->rendezVous->count() > 0)
                <div class="space-y-3">
                    @foreach($patient->rendezVous as $rdv)
                        <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">{{ $rdv->date_heure->format('d/m/Y H:i') }}</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $rdv->motif }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ ucfirst($rdv->statut) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucun rendez-vous</p>
            @endif
        </div>
    </div>
@endsection
