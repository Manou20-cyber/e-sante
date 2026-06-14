<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Suivi Médical Optique</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900">{{ config('app.name') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    Se connecter
                </a>
                <a href="{{ route('register') }}"
                   class="text-sm font-medium px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    S'inscrire
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-blue-900 to-blue-700 text-white py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-blue-800/50 rounded-full px-4 py-1.5 text-sm text-blue-200 mb-6">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                Plateforme de Suivi Médical Optique
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold leading-tight mb-6">
                Votre santé visuelle,<br class="hidden sm:block"> gérée simplement
            </h1>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto leading-relaxed">
                Connectez patients et cabinets optiques sur une plateforme sécurisée.
                Rendez-vous, ordonnances, commandes — tout en un seul endroit.
            </p>
        </div>
    </section>

    {{-- Cards d'inscription --}}
    <section class="max-w-5xl mx-auto px-4 sm:px-6 -mt-12 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Card Patient --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-8 py-8">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Je suis patient</h2>
                    <p class="text-gray-500 text-sm">Accédez à votre espace santé personnalisé</p>
                </div>

                <div class="px-8 py-6 flex-1 flex flex-col">
                    <ul class="space-y-3 mb-8 flex-1">
                        @foreach([
                            'Réserver des rendez-vous en ligne',
                            'Consulter mes ordonnances et résultats',
                            'Commander mes lunettes prescrites',
                            'Messagerie avec mon cabinet',
                            'Historique médical complet',
                        ] as $feature)
                            <li class="flex items-start gap-3 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="space-y-2">
                        <a href="{{ route('register') }}"
                           class="block w-full text-center py-3 px-6 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                            Créer mon espace patient
                        </a>
                        <p class="text-center text-xs text-gray-400">
                            Déjà inscrit ?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card Cabinet Optique --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-br from-teal-50 to-teal-100 px-8 py-8">
                    <div class="w-14 h-14 bg-teal-600 rounded-2xl flex items-center justify-center mb-4 shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Je suis un cabinet optique</h2>
                    <p class="text-gray-500 text-sm">Gérez votre activité de A à Z</p>
                </div>

                <div class="px-8 py-6 flex-1 flex flex-col">
                    <ul class="space-y-3 mb-6 flex-1">
                        @foreach([
                            'Gestion des rendez-vous et créneaux',
                            'Dossiers médicaux des patients',
                            'Stock de montures et lentilles',
                            'Facturation et paiements',
                            "Rapports d'activité",
                        ] as $feature)
                            <li class="flex items-start gap-3 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="space-y-3">
                        <div class="flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-amber-700 leading-relaxed">
                                Votre compte sera examiné et validé par notre équipe avant activation complète.
                            </p>
                        </div>

                        <a href="{{ route('register.cabinet') }}"
                           class="block w-full text-center py-3 px-6 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-sm">
                            Inscrire mon cabinet
                        </a>
                        <p class="text-center text-xs text-gray-400">
                            Déjà inscrit ?
                            <a href="{{ route('login') }}" class="text-teal-600 hover:underline">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-6 text-center text-sm text-gray-400">
        © {{ date('Y') }} {{ config('app.name') }} — Plateforme de Suivi Médical Optique
    </footer>

</body>
</html>
