@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
                <p class="text-gray-600 mt-1">Bienvenue, {{ auth()->user()->full_name }}</p>
            </div>
            @can('create patient')
                <a href="{{ route('patients.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">+ Nouveau Patient</a>
            @endcan
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('patients.index') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition cursor-pointer">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm font-medium text-gray-600">Patients totaux</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_patients'] }}</p></div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                </div>
            </a>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm font-medium text-gray-600">Consultations du jour</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today_consultations'] }}</p></div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm font-medium text-gray-600">Patients hospitalisés</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['hospitalized_patients'] }}</p></div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm font-medium text-gray-600">Rendez-vous du jour</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today_appointments'] }}</p></div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm font-medium text-gray-600">Prescriptions du jour</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today_prescriptions'] }}</p></div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                </div>
            </div>
            <a href="{{ route('laboratory.index') }}" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition cursor-pointer">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm font-medium text-gray-600">Analyses en attente</p><p class="text-2xl font-bold {{ $stats['pending_analyses'] > 0 ? 'text-yellow-600' : 'text-gray-900' }} mt-1">{{ $stats['pending_analyses'] }}</p></div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 9h-6L8 4z"></path></svg></div>
                </div>
            </a>
            <a href="{{ route('pharmacy.index') }}?low_stock=1" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition cursor-pointer">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm font-medium text-gray-600">Médicaments stock faible</p><p class="text-2xl font-bold {{ $stats['low_stock_medications'] > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">{{ $stats['low_stock_medications'] }}</p></div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg></div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if($recentConsultations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Consultations récentes</h2>
                    <div class="space-y-3">
                        @foreach($recentConsultations as $consultation)
                            <div class="flex justify-between items-start bg-gray-50 rounded-lg p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</p>
                                    <p class="text-sm text-gray-600">{{ $consultation->motif }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $consultation->date_consultation->format('d/m/Y H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($pendingLabAnalyses->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Analyses en attente</h2>
                    <div class="space-y-3">
                        @foreach($pendingLabAnalyses as $analysis)
                            <div class="flex justify-between items-start bg-gray-50 rounded-lg p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $analysis->analysis_type }}</p>
                                    <p class="text-sm text-gray-600">{{ $analysis->patient->nom }} {{ $analysis->patient->prenom }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($analysis->status) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-8 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Rôles et permissions</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700">Votre rôle: <span class="font-semibold">{{ auth()->user()->roles->first()->name ?? 'Non défini' }}</span></p>
            </div>
        </div>
@endsection
