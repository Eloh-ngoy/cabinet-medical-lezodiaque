@extends('layouts.app')

@section('title', 'Détails Rendez-vous')

@section('content')
    <div class="mb-8">
        <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour aux rendez-vous
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Détails du Rendez-vous</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Patient</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $appointment->patient->nom }} {{ $appointment->patient->prenom }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $appointment->patient->numero_unique }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date et heure</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $appointment->date_heure->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Motif</p>
                <p class="text-lg font-medium text-gray-900 mt-1">{{ $appointment->motif }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Statut</p>
                <span class="inline-block mt-1 px-3 py-1 text-sm font-medium rounded-full 
                    @if($appointment->statut == 'planifie') bg-yellow-100 text-yellow-800
                    @elseif($appointment->statut == 'confirme') bg-green-100 text-green-800
                    @elseif($appointment->statut == 'annule') bg-red-100 text-red-800
                    @elseif($appointment->statut == 'termine') bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst($appointment->statut) }}
                </span>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            @if(auth()->user()->can('edit appointment'))
                <a href="{{ route('appointments.edit', $appointment) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Modifier
                </a>
            @endif
        </div>
    </div>
@endsection
