@extends('layouts.app')

@section('title', 'Détails Médicament')

@section('content')
    <div class="mb-8">
        <a href="{{ route('pharmacy.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Retour à la pharmacie
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $pharmacy->name }}</h2>
                <div class="space-y-4">
                    <div class="flex justify-between"><span class="text-gray-600">Nom générique:</span><span class="text-gray-900">{{ $pharmacy->generic_name ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Catégorie:</span><span class="text-gray-900">{{ $pharmacy->category ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Unité:</span><span class="text-gray-900">{{ $pharmacy->unit }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Stock:</span><span class="text-gray-900 {{ $pharmacy->isLowStock() ? 'text-red-600 font-bold' : '' }}">{{ $pharmacy->stock_quantity }} {{ $pharmacy->unit }}(s)</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Seuil minimum:</span><span class="text-gray-900">{{ $pharmacy->min_stock_threshold }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Prix unitaire:</span><span class="text-gray-900">{{ number_format($pharmacy->unit_price, 2) }} €</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Statut:</span>
                        @if($pharmacy->isLowStock())<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Stock faible</span>
                        @else<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">OK</span>@endif
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    @if(auth()->user()->can('manage users'))
                        <a href="{{ route('pharmacy.edit', $pharmacy) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Modifier</a>
                    @endif
                    <button onclick="document.getElementById('dispense-form').classList.toggle('hidden')" class="block w-full text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">Délivrer</button>
                    <button onclick="document.getElementById('restock-form').classList.toggle('hidden')" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Réapprovisionner</button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if($pharmacy->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                    <p class="text-gray-700">{{ $pharmacy->description }}</p>
                </div>
            @endif

            <div id="dispense-form" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Délivrer un médicament</h3>
                <form method="POST" action="{{ route('pharmacy.dispense', $pharmacy) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-2">Quantité</label><input type="number" name="quantity" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-2">Raison</label><input type="text" name="reason" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></div>
                    </div>
                    <button type="submit" class="mt-4 px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">Confirmer</button>
                </form>
            </div>

            <div id="restock-form" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Réapprovisionner</h3>
                <form method="POST" action="{{ route('pharmacy.restock', $pharmacy) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-2">Quantité</label><input type="number" name="quantity" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-2">Raison</label><input type="text" name="reason" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></div>
                    </div>
                    <button type="submit" class="mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Confirmer</button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des mouvements ({{ $pharmacy->movements->count() }})</h3>
                @if($pharmacy->movements->count() > 0)
                    <div class="space-y-3">
                        @foreach($pharmacy->movements->sortByDesc('created_at') as $movement)
                            <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ ['entree' => 'Entrée', 'sortie' => 'Sortie', 'delivrance' => 'Délivrance'][$movement->movement_type] ?? $movement->movement_type }}
                                        — {{ $movement->quantity }} {{ $pharmacy->unit }}(s)
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $movement->reason ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">{{ $movement->user?->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">{{ $movement->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucun mouvement enregistré</p>
                @endif
            </div>
        </div>
    </div>
@endsection
