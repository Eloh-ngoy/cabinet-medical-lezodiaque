@extends('layouts.app')

@section('title', 'Détails Hospitalisation')

@section('content')
    <div class="mb-8">
        <a href="{{ route('hospitalizations.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour aux hospitalisations
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Informations</h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Patient:</span>
                        <a href="{{ route('patients.show', $hospitalization->patient) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $hospitalization->patient->nom }} {{ $hospitalization->patient->prenom }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lit:</span>
                        <span class="text-gray-900">{{ $hospitalization->bed?->label() ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date d'admission:</span>
                        <span class="text-gray-900">{{ $hospitalization->admission_date->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durée prévue:</span>
                        <span class="text-gray-900">{{ $hospitalization->expected_duration ? $hospitalization->expected_duration . ' jours' : 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut:</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $hospitalization->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $hospitalization->status === 'active' ? 'En cours' : 'Sorti' }}
                        </span>
                    </div>
                    @if($hospitalization->discharge_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date de sortie:</span>
                            <span class="text-gray-900">{{ $hospitalization->discharge_date->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-6 space-y-3">
                    @if(auth()->user()->can('edit hospitalization'))
                        <a href="{{ route('hospitalizations.edit', $hospitalization) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Modifier
                        </a>
                    @endif
                    @if($hospitalization->status === 'active' && auth()->user()->can('discharge patient'))
                        <button onclick="document.getElementById('discharge-form').classList.toggle('hidden')" class="block w-full text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                            Sortir le patient
                        </button>
                    @endif
                    <a href="{{ route('hospitalizations.export.report', $hospitalization) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Rapport PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if($hospitalization->admission_reason)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Motif d'admission</h3>
                    <p class="text-gray-700">{{ $hospitalization->admission_reason }}</p>
                </div>
            @endif

            @if($hospitalization->discharge_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes de sortie</h3>
                    <p class="text-gray-700">{{ $hospitalization->discharge_notes }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Posologies ({{ $hospitalization->posologies->count() }})</h3>
                @if($hospitalization->posologies->count() > 0)
                    <div class="space-y-3">
                        @foreach($hospitalization->posologies as $posology)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $posology->medication_name }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $posology->dosage }} — {{ $posology->frequency }}</p>
                                        @if($posology->duration)
                                            <p class="text-sm text-gray-600">Durée: {{ $posology->duration }}</p>
                                        @endif
                                        @if($posology->instructions)
                                            <p class="text-sm text-gray-500 mt-1">{{ $posology->instructions }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $posology->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($posology->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucune posologie enregistrée</p>
                @endif
            </div>

            @if($hospitalization->status === 'active' && auth()->user()->can('discharge patient'))
                <div id="discharge-form" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Sortie du patient</h3>
                    <form method="POST" action="{{ route('hospitalizations.discharge', $hospitalization) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date de sortie</label>
                                <input type="datetime-local" name="discharge_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ now()->format('Y-m-d\TH:i') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes de sortie</label>
                                <textarea name="discharge_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">Confirmer la sortie</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
