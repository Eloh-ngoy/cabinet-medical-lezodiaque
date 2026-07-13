@extends('layouts.app')

@section('title', 'Nouvelle Analyse')

@section('content')
    <div class="mb-8">
        <a href="{{ route('laboratory.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Retour au laboratoire
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Nouvelle Demande d'Analyse</h1>
    </div>

    <form method="POST" action="{{ route('laboratory.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                <select name="patient_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionner un patient</option>
                    @foreach($patients as $patient)<option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->nom }} {{ $patient->prenom }} ({{ $patient->numero_unique }})</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Consultation (optionnel)</label>
                <select name="consultation_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Aucune consultation associée</option>
                    @foreach($consultations as $consultation)<option value="{{ $consultation->id }}" {{ old('consultation_id') == $consultation->id ? 'selected' : '' }}>{{ $consultation->date_consultation->format('d/m/Y') }} — {{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type d'analyse</label>
                <select name="analysis_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionner</option>
                    @foreach($analysisTypes as $type)<option value="{{ $type }}" {{ old('analysis_type') === $type ? 'selected' : '' }}>{{ $type }}</option>@endforeach
                    <option value="autre" {{ old('analysis_type') === 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description / Notes</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('laboratory.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Annuler</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Créer la demande</button>
        </div>
    </form>
@endsection
