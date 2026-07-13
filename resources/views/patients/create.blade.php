@extends('layouts.app')

@section('title', 'Nouveau Patient')

@section('content')
        <div class="mb-8">
            <a href="{{ route('patients.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux patients
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-4">Nouveau Patient</h1>
        </div>

        <form method="POST" action="{{ route('patients.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                    <input type="text" name="nom" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('nom') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                    <input type="text" name="prenom" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('prenom') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('email') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input type="text" name="telephone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('telephone') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                    <input type="date" name="date_naissance" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('date_naissance') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                    <select name="sexe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="homme" {{ old('sexe') === 'homme' ? 'selected' : '' }}>Homme</option>
                        <option value="femme" {{ old('sexe') === 'femme' ? 'selected' : '' }}>Femme</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Groupe sanguin</label>
                    <input type="text" name="groupe_sanguin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('groupe_sanguin') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="statut_interne_externe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="externe" {{ old('statut_interne_externe') === 'externe' ? 'selected' : '' }}>Externe</option>
                        <option value="interne" {{ old('statut_interne_externe') === 'interne' ? 'selected' : '' }}>Interne</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                    <input type="text" name="adresse" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('adresse') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact urgence (Nom)</label>
                    <input type="text" name="contact_urgence_nom" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('contact_urgence_nom') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact urgence (Téléphone)</label>
                    <input type="text" name="contact_urgence_telephone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('contact_urgence_telephone') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Traitement passé</label>
                    <textarea name="traitement_passe" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('traitement_passe') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('patients.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Annuler</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Créer le patient</button>
            </div>
        </form>
@endsection
