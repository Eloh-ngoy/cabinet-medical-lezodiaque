@extends('layouts.app')

@section('title', 'Détails Consultation')

@section('content')
    <div class="mb-8">
        <a href="{{ route('consultations.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour aux consultations
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Détails de la Consultation</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Patient</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $consultation->patient->numero_unique }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date de consultation</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $consultation->date_consultation->format('d/m/Y H:i') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Motif</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $consultation->motif }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Diagnostic</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $consultation->diagnostic ?? 'Non renseigné' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Traitement</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $consultation->traitement ?? 'Non renseigné' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Ordonnance</p>
                <p class="text-lg font-medium text-gray-900 mt-1 whitespace-pre-line">{{ $consultation->ordonnance ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Prix</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ number_format($consultation->prix, 0) }} CDF</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            @if(auth()->user()->can('edit consultation'))
                <a href="{{ route('consultations.edit', $consultation) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Modifier
                </a>
            @endif
        </div>
    </div>
@endsection
