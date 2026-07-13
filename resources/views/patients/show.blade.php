@extends('layouts.app')

@section('title', 'Détails Patient')

@section('content')
        <div class="mb-8">
            <a href="{{ route('patients.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux patients
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $patient->nom }} {{ $patient->prenom }}</h2>
                        <p class="text-gray-600 mt-1">{{ $patient->numero_unique }}</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date de naissance:</span>
                            <span class="text-gray-900">{{ $patient->date_naissance->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sexe:</span>
                            <span class="text-gray-900">{{ $patient->sexe }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Groupe sanguin:</span>
                            <span class="text-gray-900">{{ $patient->groupe_sanguin ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Statut:</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $patient->statut_interne_externe === 'interne' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $patient->statut_interne_externe }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        @if(auth()->user()->can('edit patient'))
                            <a href="{{ route('patients.edit', $patient) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Modifier
                            </a>
                        @endif
                        @if(auth()->user()->can('create consultation'))
                            <a href="{{ route('patients.consultations.create', $patient) }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                + Nouvelle Consultation
                            </a>
                        @endif
                        @if(auth()->user()->can('create appointment'))
                            <a href="{{ route('patients.appointments.create', $patient) }}" class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                + Nouveau Rendez-vous
                            </a>
                        @endif
                        @if(auth()->user()->can('export medical record') || auth()->user()->can('export medical summary'))
                            <a href="{{ route('patients.export', $patient) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                📄 Exports PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-gray-900">{{ $patient->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Téléphone</p>
                            <p class="text-gray-900">{{ $patient->telephone }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Adresse</p>
                            <p class="text-gray-900">{{ $patient->adresse ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Contact urgence (Nom)</p>
                            <p class="text-gray-900">{{ $patient->contact_urgence_nom ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Contact urgence (Téléphone)</p>
                            <p class="text-gray-900">{{ $patient->contact_urgence_telephone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations médicales</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Traitement passé</p>
                            <p class="text-gray-900">{{ $patient->traitement_passe ?? 'Aucun' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Allergies</p>
                            <p class="text-gray-900">{{ $patient->allergies ? implode(', ', $patient->allergies) : 'Aucune' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Antécédents</p>
                            <p class="text-gray-900">{{ $patient->antecedents ? implode(', ', $patient->antecedents) : 'Aucun' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Maladies chroniques</p>
                            <p class="text-gray-900">{{ $patient->maladies_chroniques ? implode(', ', $patient->maladies_chroniques) : 'Aucune' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-2xl font-bold text-blue-600">{{ $patient->consultations->count() }}</p>
                            <p class="text-sm text-gray-600">Consultations</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-2xl font-bold text-green-600">{{ $patient->rendezVous->count() }}</p>
                            <p class="text-sm text-gray-600">Rendez-vous</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4">
                            <p class="text-2xl font-bold text-orange-600">{{ $patient->hospitalizations->count() }}</p>
                            <p class="text-sm text-gray-600">Hospitalisations</p>
                        </div>
                    </div>

                    @if($patient->consultations->count() > 0)
                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-3">Consultations récentes</h4>
                            <div class="space-y-3">
                                @foreach($patient->consultations->sortByDesc('date_consultation')->take(5) as $consultation)
                                    @if(auth()->user()->can('view consultation details'))
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-sm text-gray-600">Date: {{ $consultation->date_consultation->format('d/m/Y H:i') }}</p>
                                                    <p class="text-sm font-medium text-gray-900 mt-1">Motif: {{ $consultation->motif }}</p>
                                                    @if($consultation->diagnostic)
                                                        <p class="text-sm text-gray-700 mt-1">Diagnostic: {{ $consultation->diagnostic }}</p>
                                                    @endif
                                                </div>
                                                @if(auth()->user()->can('view consultation details'))
                                                    <a href="{{ route('consultations.show', $consultation) }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir détails</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($patient->rendezVous->count() > 0)
                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-3">Rendez-vous</h4>
                            <div class="space-y-3">
                                @foreach($patient->rendezVous->sortByDesc('date_heure')->take(5) as $rdv)
                                    @if(auth()->user()->can('view appointment details'))
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-sm text-gray-600">Date: {{ $rdv->date_heure->format('d/m/Y H:i') }}</p>
                                                    <p class="text-sm font-medium text-gray-900 mt-1">Motif: {{ $rdv->motif }}</p>
                                                    <p class="text-sm text-gray-700 mt-1">Statut: 
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                            @if($rdv->statut == 'planifie') bg-yellow-100 text-yellow-800
                                                            @elseif($rdv->statut == 'confirme') bg-green-100 text-green-800
                                                            @elseif($rdv->statut == 'annule') bg-red-100 text-red-800
                                                            @elseif($rdv->statut == 'termine') bg-blue-100 text-blue-800
                                                            @endif">
                                                            {{ ucfirst($rdv->statut) }}
                                                        </span>
                                                    </p>
                                                </div>
                                                @if(auth()->user()->can('view appointment details'))
                                                    <a href="{{ route('appointments.show', $rdv) }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir détails</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
@endsection
