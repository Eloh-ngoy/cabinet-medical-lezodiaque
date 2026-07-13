@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous')

@section('content')
    <div class="mb-8">
        <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour aux rendez-vous
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Nouveau Rendez-vous</h1>
    </div>

    <form method="POST" action="{{ route('appointments.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                <select name="patient_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionner un patient</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ $patient && $patient->id == $p->id ? 'selected' : '' }}>{{ $p->nom }} {{ $p->prenom }} ({{ $p->numero_unique }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure</label>
                <input type="datetime-local" name="date_heure" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Motif</label>
                <input type="text" name="motif" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="statut" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="planifie">Planifié</option>
                    <option value="confirme">Confirmé</option>
                    <option value="annule">Annulé</option>
                    <option value="termine">Terminé</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('appointments.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Annuler</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Créer le rendez-vous</button>
        </div>
    </form>
@endsection
