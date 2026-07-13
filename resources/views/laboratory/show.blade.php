@extends('layouts.app')

@section('title', 'Détails Analyse')

@section('content')
    <div class="mb-8">
        <a href="{{ route('laboratory.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Retour au laboratoire
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $laboratory->analysis_type }}</h2>
                <div class="space-y-4">
                    <div class="flex justify-between"><span class="text-gray-600">Patient:</span><a href="{{ route('patients.show', $laboratory->patient) }}" class="text-blue-600 hover:text-blue-800">{{ $laboratory->patient->nom }} {{ $laboratory->patient->prenom }}</a></div>
                    <div class="flex justify-between"><span class="text-gray-600">Demandée par:</span><span class="text-gray-900">{{ $laboratory->requestedBy?->full_name ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Date demande:</span><span class="text-gray-900">{{ $laboratory->requested_at->format('d/m/Y H:i') }}</span></div>
                    @if($laboratory->completed_at)<div class="flex justify-between"><span class="text-gray-600">Date complétion:</span><span class="text-gray-900">{{ $laboratory->completed_at->format('d/m/Y H:i') }}</span></div>@endif
                    @if($laboratory->validated_at)<div class="flex justify-between"><span class="text-gray-600">Date validation:</span><span class="text-gray-900">{{ $laboratory->validated_at->format('d/m/Y H:i') }}</span></div>@endif
                    <div class="flex justify-between"><span class="text-gray-600">Statut:</span>
                        @php $statusColors = ['demandee' => 'bg-yellow-100 text-yellow-800', 'en_cours' => 'bg-blue-100 text-blue-800', 'terminee' => 'bg-purple-100 text-purple-800', 'validee' => 'bg-green-100 text-green-800']; $statusLabels = ['demandee' => 'Demandée', 'en_cours' => 'En cours', 'terminee' => 'Terminée', 'validee' => 'Validée']; @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$laboratory->status] ?? 'bg-gray-100 text-gray-800' }}">{{ $statusLabels[$laboratory->status] ?? $laboratory->status }}</span>
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    @if($laboratory->status === 'demandee' && auth()->user()->can('enter lab results'))
                        <a href="{{ route('laboratory.edit', $laboratory) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Saisir les résultats</a>
                    @endif
                    @if($laboratory->status === 'terminee' && auth()->user()->can('validate lab results'))
                        <form action="{{ route('laboratory.validate', $laboratory) }}" method="POST">@csrf @method('PUT')<button type="submit" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition" onclick="return confirm('Valider ces résultats ?')">Valider les résultats</button></form>
                    @endif
                    @if($laboratory->status === 'validee')
                        <a href="{{ route('analysis.export.report', $laboratory) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Rapport PDF</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if($laboratory->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                    <p class="text-gray-700">{{ $laboratory->description }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Résultats</h3>
                @if($laboratory->results)
                    <pre class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-wrap">{{ $laboratory->results }}</pre>
                @else
                    <p class="text-gray-500">Aucun résultat saisi pour le moment</p>
                @endif
            </div>

            @if($laboratory->validatedBy)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Validation</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-700">Validé par: <span class="font-medium">{{ $laboratory->validatedBy->full_name }}</span></p>
                        <p class="text-sm text-gray-700">Date: {{ $laboratory->validated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
