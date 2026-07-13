@extends('layouts.app')

@section('title', 'Rendez-vous')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rendez-vous</h1>
            <p class="text-gray-600 mt-1">Gestion des rendez-vous</p>
        </div>
        @if(auth()->user()->can('create appointment'))
            <a href="{{ route('appointments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                + Nouveau Rendez-vous
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <form method="GET" action="{{ route('appointments.index') }}" class="flex gap-3 items-center">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" placeholder="Rechercher par patient, motif..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="planifie" {{ ($status ?? '') === 'planifie' ? 'selected' : '' }}>Planifié</option>
                    <option value="confirme" {{ ($status ?? '') === 'confirme' ? 'selected' : '' }}>Confirmé</option>
                    <option value="annule" {{ ($status ?? '') === 'annule' ? 'selected' : '' }}>Annulé</option>
                    <option value="termine" {{ ($status ?? '') === 'termine' ? 'selected' : '' }}>Terminé</option>
                </select>
                <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm">Filtrer</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->date_heure->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->patient->nom }} {{ $appointment->patient->prenom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->motif }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($appointment->statut == 'planifie') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->statut == 'confirme') bg-green-100 text-green-800
                                    @elseif($appointment->statut == 'annule') bg-red-100 text-red-800
                                    @elseif($appointment->statut == 'termine') bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($appointment->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if(auth()->user()->can('edit appointment'))
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="text-gray-600 hover:text-gray-900 mr-3">Modifier</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Aucun rendez-vous trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $appointments->links() }}
        </div>
    </div>
@endsection
