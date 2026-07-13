@extends('layouts.app')

@section('title', 'Modifier Hospitalisation')

@section('content')
    <div class="mb-8">
        <a href="{{ route('hospitalizations.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour aux hospitalisations
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Modifier l'Hospitalisation</h1>
    </div>

    <form method="POST" action="{{ route('hospitalizations.update', $hospitalization) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf
        @method('PUT')

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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                <select name="patient_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id', $hospitalization->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->nom }} {{ $patient->prenom }} ({{ $patient->numero_unique }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lit</label>
                <select name="bed_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($beds as $bed)
                        <option value="{{ $bed->id }}" {{ old('bed_id', $hospitalization->bed_id) == $bed->id ? 'selected' : '' }}>
                            {{ $bed->label() }} — {{ $bed->bed_type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date d'admission</label>
                <input type="datetime-local" name="admission_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('admission_date', $hospitalization->admission_date->format('Y-m-d\TH:i')) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durée prévue (jours)</label>
                <input type="number" name="expected_duration" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('expected_duration', $hospitalization->expected_duration) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'admission</label>
                <textarea name="admission_reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('admission_reason', $hospitalization->admission_reason) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('hospitalizations.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Annuler</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Mettre à jour</button>
        </div>
    </form>
@endsection
