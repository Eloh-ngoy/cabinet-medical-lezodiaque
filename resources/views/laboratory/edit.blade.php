@extends('layouts.app')

@section('title', 'Saisir Résultats')

@section('content')
    <div class="mb-8">
        <a href="{{ route('laboratory.show', $laboratory) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Retour à l'analyse
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Saisir les Résultats</h1>
        <p class="text-gray-600 mt-1">{{ $laboratory->analysis_type }} — {{ $laboratory->patient->nom }} {{ $laboratory->patient->prenom }}</p>
    </div>

    <form method="POST" action="{{ route('laboratory.update', $laboratory) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf @method('PUT')
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Résultats de l'analyse</label>
            <textarea name="results" required rows="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm" placeholder="Saisissez les résultats de l'analyse...">{{ old('results', $laboratory->results) }}</textarea>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('laboratory.show', $laboratory) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Annuler</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Enregistrer les résultats</button>
        </div>
    </form>
@endsection
