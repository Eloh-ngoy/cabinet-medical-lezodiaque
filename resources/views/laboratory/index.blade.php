@extends('layouts.app')

@section('title', 'Laboratoire')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laboratoire</h1>
            <p class="text-gray-600 mt-1">Demandes et résultats d'analyses</p>
        </div>
        @if(auth()->user()->can('create lab request'))
            <a href="{{ route('laboratory.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">+ Nouvelle Analyse</a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <form method="GET" action="{{ route('laboratory.index') }}" class="flex gap-3 items-center">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" placeholder="Rechercher par patient, type d'analyse..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="demandee" {{ ($status ?? '') === 'demandee' ? 'selected' : '' }}>Demandée</option>
                    <option value="en_cours" {{ ($status ?? '') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="terminee" {{ ($status ?? '') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                    <option value="validee" {{ ($status ?? '') === 'validee' ? 'selected' : '' }}>Validée</option>
                </select>
                <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm">Filtrer</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandée par</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($analyses as $analysis)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $analysis->analysis_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $analysis->patient->nom }} {{ $analysis->patient->prenom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $analysis->requestedBy?->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $analysis->requested_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $statusColors = ['demandee' => 'bg-yellow-100 text-yellow-800', 'en_cours' => 'bg-blue-100 text-blue-800', 'terminee' => 'bg-purple-100 text-purple-800', 'validee' => 'bg-green-100 text-green-800'];
                                    $statusLabels = ['demandee' => 'Demandée', 'en_cours' => 'En cours', 'terminee' => 'Terminée', 'validee' => 'Validée'];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$analysis->status] ?? 'bg-gray-100 text-gray-800' }}">{{ $statusLabels[$analysis->status] ?? $analysis->status }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('laboratory.show', $analysis) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                @if($analysis->status === 'demandee' && auth()->user()->can('enter lab results'))
                                    <a href="{{ route('laboratory.edit', $analysis) }}" class="text-gray-600 hover:text-gray-900 mr-3">Saisir résultats</a>
                                @endif
                                @if($analysis->status === 'terminee' && auth()->user()->can('validate lab results'))
                                    <form action="{{ route('laboratory.validate', $analysis) }}" method="POST" class="inline">@csrf @method('PUT')<button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Valider ces résultats ?')">Valider</button></form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Aucune analyse trouvée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">{{ $analyses->links() }}</div>
    </div>
@endsection
