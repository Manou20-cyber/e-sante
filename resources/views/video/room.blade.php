<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultation vidéo — {{ $appSettings['nom'] }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css'])
    @include('partials.theme')
    <style>
        #jitsi-container { width: 100%; height: 100%; }
        body { overflow: hidden; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-900">

<div class="flex flex-col h-screen">

    {{-- Barre supérieure --}}
    <div class="th-sidebar flex items-center justify-between px-4 py-2.5 shrink-0 z-10 shadow-lg">
        <div class="flex items-center gap-3">
            @if($appSettings['logo'])
                <img src="{{ Storage::url($appSettings['logo']) }}" alt="Logo"
                     class="w-7 h-7 rounded-lg object-contain bg-white p-0.5">
            @else
                <div class="th-logo-bg w-7 h-7 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            @endif
            <div>
                <p class="text-white text-sm font-semibold leading-tight">Consultation vidéo</p>
                <p class="th-sidebar-muted text-xs">
                    @if($role === 'opticien')
                        Patient : {{ $rendezvous->patient->user->name }}
                    @else
                        Opticien : {{ $rendezvous->opticien?->name ?? $rendezvous->cabinet->nom }}
                    @endif
                    <span class="mx-1 opacity-40">·</span>
                    {{ $rendezvous->date->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- Indicateur "en cours" --}}
            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-green-500/20 border border-green-500/30 rounded-full">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-green-300 text-xs font-medium">En cours</span>
            </div>

            {{-- Bouton retour --}}
            @if($role === 'opticien')
                <a href="{{ route('admin.rendezvous.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-white/10 hover:bg-white/20 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quitter
                </a>
            @else
                <a href="{{ route('patient.rendezvous.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-white/10 hover:bg-white/20 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quitter
                </a>
            @endif
        </div>
    </div>

    {{-- Zone vidéo principale --}}
    <div class="flex-1 relative">
        <div id="jitsi-container" class="absolute inset-0"></div>

        {{-- Écran de chargement --}}
        <div id="loading-screen"
             class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 z-10">
            <div class="w-16 h-16 rounded-2xl th-logo-bg flex items-center justify-center mb-4 shadow-xl">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.069A1 1 0 0121 8.868v6.264a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-white font-semibold text-lg mb-1">Connexion à la salle…</p>
            <p class="text-gray-400 text-sm mb-6">Autorisez l'accès à votre caméra et microphone</p>
            <div class="flex gap-1">
                <span class="w-2 h-2 bg-white/40 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                <span class="w-2 h-2 bg-white/40 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                <span class="w-2 h-2 bg-white/40 rounded-full animate-bounce" style="animation-delay:300ms"></span>
            </div>
        </div>
    </div>
</div>

{{-- Jitsi Meet External API --}}
<script src="https://meet.jit.si/external_api.js"></script>
<script>
    const roomName   = @json($roomName);
    const userName   = @json($userName);
    const role       = @json($role);
    const patientName = @json($rendezvous->patient->user->name ?? '');
    const opticienName = @json($rendezvous->opticien?->name ?? '');

    const api = new JitsiMeetExternalAPI('meet.jit.si', {
        roomName: roomName,
        parentNode: document.querySelector('#jitsi-container'),
        userInfo: {
            displayName: userName,
            email: '',
        },
        configOverwrite: {
            startWithAudioMuted: false,
            startWithVideoMuted: false,
            enableWelcomePage: false,
            disableDeepLinking: true,
            prejoinPageEnabled: false,
            defaultLanguage: 'fr',
            toolbarButtons: [
                'microphone', 'camera', 'closedcaptions', 'desktop',
                'fullscreen', 'fodeviceselection', 'hangup', 'chat',
                'settings', 'raisehand', 'videoquality', 'tileview',
            ],
        },
        interfaceConfigOverwrite: {
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            SHOW_BRAND_WATERMARK: false,
            BRAND_WATERMARK_LINK: '',
            DEFAULT_BACKGROUND: '#111827',
            TOOLBAR_ALWAYS_VISIBLE: false,
            MOBILE_APP_PROMO: false,
        },
        lang: 'fr',
    });

    const hideLoading = () => {
        const el = document.getElementById('loading-screen');
        if (el) el.style.display = 'none';
    };

    // Signal primaire : Jitsi a rejoint la conférence
    api.addEventListener('videoConferenceJoined', hideLoading);
    api.addEventListener('videoConferenceCreated', hideLoading);

    // Signal secondaire : l'iframe Jitsi est chargée (pre-join page visible ou connexion en cours)
    const iframe = api.getIFrame();
    if (iframe) {
        iframe.addEventListener('load', hideLoading);
    }

    // Fallback garanti : masquer l'écran après 6 secondes dans tous les cas
    setTimeout(hideLoading, 6000);

    api.addEventListener('readyToClose', () => {
        window.location.href = role === 'opticien'
            ? '{{ route("admin.rendezvous.index") }}'
            : '{{ route("patient.rendezvous.index") }}';
    });
</script>

</body>
</html>
