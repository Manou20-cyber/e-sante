<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-gray-50">

<div class="min-h-screen flex">

    {{-- Panneau gauche — Marque (desktop uniquement) --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 flex-col justify-between p-12 relative overflow-hidden">

        {{-- Motif décoratif --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white translate-x-1/3 -translate-y-1/3"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-white -translate-x-1/3 translate-y-1/3"></div>
        </div>

        {{-- Logo --}}
        <div class="relative">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xl">{{ config('app.name') }}</span>
            </a>
        </div>

        {{-- Contenu central --}}
        <div class="relative">
            <h2 class="text-white text-3xl font-bold leading-snug mb-4">
                Votre santé visuelle,<br>entre de bonnes mains.
            </h2>
            <p class="text-blue-200 text-base leading-relaxed mb-8">
                Plateforme sécurisée de suivi médical optique pour patients et cabinets.
            </p>

            <div class="space-y-3">
                @foreach([
                    ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'text' => 'Rendez-vous en ligne'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'text' => 'Dossiers médicaux sécurisés'],
                    ['icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'text' => 'Commandes de lunettes'],
                ] as $item)
                    <div class="flex items-center gap-3 text-blue-100 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        {{ $item['text'] }}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Footer gauche --}}
        <div class="relative">
            <p class="text-blue-300 text-xs">© {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>

    {{-- Panneau droit — Formulaire --}}
    <div class="flex-1 flex flex-col justify-center px-6 py-10 sm:px-10 lg:px-16 xl:px-24 bg-white">

        {{-- Logo mobile --}}
        <div class="lg:hidden flex justify-center mb-8">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900">{{ config('app.name') }}</span>
            </a>
        </div>

        <div class="w-full max-w-md mx-auto">
            {{ $slot }}
        </div>
    </div>

</div>

</body>
</html>
