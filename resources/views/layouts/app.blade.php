<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediNexus') - MediNexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">MediNexus</span>
                    </div>
                    <div class="ml-6 flex space-x-4">
                        @php
                            $navItems = [
                                ['route' => 'dashboard', 'label' => 'Tableau de bord', 'pattern' => 'dashboard', 'permission' => null],
                                ['route' => 'patients.index', 'label' => 'Patients', 'pattern' => 'patients.*', 'permission' => null],
                                ['route' => 'consultations.index', 'label' => 'Consultations', 'pattern' => 'consultations.*', 'permission' => 'view consultations'],
                                ['route' => 'appointments.index', 'label' => 'Rendez-vous', 'pattern' => 'appointments.*', 'permission' => 'view appointments'],
                                ['route' => 'hospitalizations.index', 'label' => 'Hospitalisations', 'pattern' => 'hospitalizations.*', 'permission' => 'view hospitalizations'],
                                ['route' => 'laboratory.index', 'label' => 'Laboratoire', 'pattern' => 'laboratory.*', 'permission' => 'view lab requests'],
                                ['route' => 'pharmacy.index', 'label' => 'Pharmacie', 'pattern' => 'pharmacy.*', 'permission' => 'dispense medication'],
                                ['route' => 'users.index', 'label' => 'Utilisateurs', 'pattern' => 'users.*', 'permission' => 'manage users'],
                            ];
                        @endphp
                        @foreach ($navItems as $item)
                            @if ($item['permission'] === null || auth()->user()->can($item['permission']))
                                <a href="{{ route($item['route']) }}"
                                   class="{{ request()->routeIs($item['pattern']) ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }} px-3 py-2 text-sm font-medium">
                                    {{ $item['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">{{ auth()->user()->full_name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-lg mb-6">
                {{ session('warning') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
