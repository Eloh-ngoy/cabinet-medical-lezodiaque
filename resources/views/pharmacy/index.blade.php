@extends('layouts.app')

@section('title', 'Pharmacie')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pharmacie</h1>
            <p class="text-gray-600 mt-1">Gestion des stocks et délivrance</p>
        </div>
        @if(auth()->user()->can('manage users'))
            <a href="{{ route('pharmacy.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">+ Nouveau Médicament</a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <form method="GET" action="{{ route('pharmacy.index') }}" class="flex gap-3 items-center">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" placeholder="Rechercher un médicament..." value="{{ $search ?? '' }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <label class="flex items-center gap-2 whitespace-nowrap text-sm text-gray-700">
                    <input type="checkbox" name="low_stock" value="1" {{ $lowStock ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    Stock faible
                </label>
                <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm">Filtrer</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seuil min.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($medications as $medication)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $medication->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medication->category ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medication->stock_quantity }} {{ $medication->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medication->min_stock_threshold }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($medication->unit_price, 2) }} €</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($medication->isLowStock())
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Stock faible</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">OK</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('pharmacy.show', $medication) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                @if(auth()->user()->can('manage users'))<a href="{{ route('pharmacy.edit', $medication) }}" class="text-gray-600 hover:text-gray-900 mr-3">Modifier</a>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Aucun médicament trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">{{ $medications->links() }}</div>
    </div>
@endsection
