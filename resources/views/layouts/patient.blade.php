<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Mon espace' }} — {{ $appSettings['nom'] }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.theme')
</head>
<body class="font-sans antialiased bg-gray-50">

<div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-on:click="sidebarOpen = false"
         class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden" style="display:none"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="th-sidebar fixed inset-y-0 left-0 w-64 flex flex-col z-30 transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b th-sidebar-border">
            @if($appSettings['logo'])
                <img src="{{ Storage::url($appSettings['logo']) }}" alt="Logo" class="w-9 h-9 rounded-xl object-contain bg-white p-0.5">
            @else
                <div class="th-logo-bg w-9 h-9 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            @endif
            <div>
                <div class="font-semibold text-sm text-white leading-tight">{{ $appSettings['nom'] }}</div>
                <div class="text-xs th-sidebar-muted">Espace patient</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            @php
                $navItems = [
                    ['route' => 'patient.dashboard',           'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Tableau de bord'],
                    ['route' => 'patient.rendezvous.index',    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Mes rendez-vous'],
                    ['route' => 'patient.dossier',             'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Dossier médical'],
                    ['route' => 'patient.documents.index',     'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10', 'label' => 'Mes documents'],
                    ['route' => 'patient.commandes.index',     'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'label' => 'Mes commandes'],
                    ['route' => 'patient.factures.index',      'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z', 'label' => 'Mes factures'],
                    ['route' => 'patient.messages.index',      'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'Messagerie'],
                    ['route' => 'patient.notifications.index', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'label' => 'Notifications'],
                    ['route' => 'patient.historique',          'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Historique'],
                ];
                $unreadMessages = auth()->user()->messagesRecus()->whereNull('lu_at')->count();
                $unreadNotifs   = auth()->user()->unreadNotifications()->count();
            @endphp

            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route'].'*') || request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition
                          {{ $isActive ? 'th-sidebar-active text-white' : 'th-sidebar-text th-sidebar-hover' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}

                    @if($item['route'] === 'patient.messages.index' && $unreadMessages > 0)
                        <span class="ml-auto text-xs bg-white/20 text-white rounded-full px-1.5 py-0.5 min-w-[1.25rem] text-center">{{ $unreadMessages }}</span>
                    @elseif($item['route'] === 'patient.notifications.index' && $unreadNotifs > 0)
                        <span class="ml-auto text-xs bg-red-500 text-white rounded-full px-1.5 py-0.5 min-w-[1.25rem] text-center">{{ $unreadNotifs }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- User --}}
        <div class="border-t th-sidebar-border px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="th-logo-bg w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs th-sidebar-muted truncate">Patient</p>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('profile.edit') }}"
                   class="flex-1 text-center text-xs th-sidebar-muted hover:text-white py-1.5 rounded-lg th-sidebar-hover transition">
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full text-xs th-sidebar-muted hover:text-white py-1.5 rounded-lg th-sidebar-hover transition">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 flex items-center gap-4 sticky top-0 z-10">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-base font-semibold text-gray-900">{{ $title ?? 'Mon espace' }}</h1>

            <div class="ml-auto flex items-center gap-3">
                @php $bellCount = auth()->user()->unreadNotifications()->count(); @endphp
                <a href="{{ route('patient.notifications.index') }}"
                   class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($bellCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            {{ $bellCount > 9 ? '9+' : $bellCount }}
                        </span>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Déconnexion"
                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="flex items-center gap-2 bg-green-50 text-green-700 text-sm px-3 py-1.5 rounded-lg border border-green-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="flex items-center gap-2 bg-red-50 text-red-700 text-sm px-3 py-1.5 rounded-lg border border-red-200">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
