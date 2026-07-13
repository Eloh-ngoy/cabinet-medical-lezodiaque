@extends('layouts.app')

@section('title', 'Nouveau Médicament')

@section('content')
    <div class="mb-8">
        <a href="{{ route('pharmacy.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Retour à la pharmacie
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4">Nouveau Médicament</h1>
    </div>

    <form method="POST" action="{{ route('pharmacy.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom commercial</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('name') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom générique</label>
                <input type="text" name="generic_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('generic_name') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionner</option>
                    @foreach($categories as $cat)<option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unité</label>
                <input type="text" name="unit" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('unit', 'boîte') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantité en stock</label>
                <input type="number" name="stock_quantity" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('stock_quantity', 0) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Seuil minimum</label>
                <input type="number" name="min_stock_threshold" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('min_stock_threshold', 10) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prix unitaire (€)</label>
                <input type="number" name="unit_price" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('unit_price', '0.00') }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('pharmacy.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Annuler</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Ajouter</button>
        </div>
    </form>
@endsection
