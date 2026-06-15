<x-patient-layout title="Tableau de bord">

    {{-- Bannière profil incomplet --}}
    @if($patient && !$patient->date_naissance)
        <div x-data="{ show: true }" x-show="show"
             class="mb-6 flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <div class="flex-1">
                <p class="font-semibold text-blue-800 text-sm">Complétez votre profil</p>
                <p class="text-xs text-blue-600 mt-0.5">Ajoutez vos informations médicales pour profiter pleinement de la plateforme.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('profile.edit') }}"
                   class="text-xs font-medium px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Compléter
                </a>
                <button @click="show = false" class="text-blue-400 hover:text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Prochain RDV --}}
    @if($prochainRdv)
        <div class="mb-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-5 text-white">
            <p class="text-blue-200 text-xs font-medium uppercase tracking-wide mb-1">Prochain rendez-vous</p>
            <p class="text-xl font-bold">{{ $prochainRdv->date->format('l d F Y à H:i') }}</p>
            <p class="text-blue-200 text-sm mt-1">{{ $prochainRdv->cabinet->nom }} — {{ $prochainRdv->type }}</p>
            <div class="mt-3 flex items-center gap-3">
                <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs font-medium">
                    {{ str_replace('_', ' ', $prochainRdv->statut) }}
                </span>
                <a href="{{ route('patient.rendezvous.index') }}" class="text-xs text-blue-200 hover:text-white underline">
                    Voir tous mes RDV →
                </a>
            </div>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'RDV à venir', 'value' => $patient?->rendezvous()->where('statut','!=','annule')->where('date','>=',now())->count() ?? 0, 'color' => 'blue', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label' => 'Messages non lus', 'value' => $messagesNonLus, 'color' => 'teal', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['label' => 'Commandes actives', 'value' => $commandesActives, 'color' => 'purple', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
            ['label' => 'Documents', 'value' => $patient?->documents()->count() ?? 0, 'color' => 'green', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10'],
        ] as $stat)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="w-9 h-9 rounded-lg bg-{{ $stat['color'] }}-100 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Accès rapide --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
        @foreach([
            ['route' => 'patient.rendezvous.index', 'label' => 'Prendre RDV', 'color' => 'bg-blue-600', 'icon' => 'M12 4v16m8-8H4'],
            ['route' => 'patient.messages.index', 'label' => 'Envoyer un message', 'color' => 'bg-teal-600', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['route' => 'patient.dossier', 'label' => 'Mon dossier', 'color' => 'bg-indigo-600', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ] as $action)
            <a href="{{ route($action['route']) }}"
               class="{{ $action['color'] }} text-white rounded-xl px-4 py-3 flex items-center gap-2 text-sm font-medium hover:opacity-90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                </svg>
                {{ $action['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Derniers rendez-vous --}}
    @if($derniersRdv && $derniersRdv->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 text-sm">Derniers rendez-vous</h3>
                <a href="{{ route('patient.rendezvous.index') }}" class="text-xs text-blue-600 hover:underline">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($derniersRdv as $rdv)
                    @php $colors = ['en_attente'=>'bg-yellow-100 text-yellow-700','confirme'=>'bg-blue-100 text-blue-700','termine'=>'bg-green-100 text-green-700','annule'=>'bg-red-100 text-red-700','absent'=>'bg-gray-100 text-gray-600']; @endphp
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $rdv->cabinet->nom }}</p>
                            <p class="text-xs text-gray-500">{{ $rdv->date->format('d/m/Y H:i') }} — {{ $rdv->type }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $colors[$rdv->statut] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ str_replace('_', ' ', $rdv->statut) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Cabinets optiques --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Cabinets optiques disponibles</h3>
            <span class="text-xs text-gray-400">{{ $cabinets->count() }} cabinet(s)</span>
        </div>

        @if($cabinets->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center text-gray-400 text-sm">
                Aucun cabinet disponible pour le moment.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($cabinets as $cabinet)
                    @php
                        $hasCreneaux = $cabinet->opticiens->some(fn($o) => $o->creneaux->isNotEmpty());
                    @endphp
                    <a href="{{ route('patient.cabinets.show', $cabinet) }}"
                       class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group p-4 flex items-start gap-4">

                        <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-200 transition">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="font-semibold text-sm text-gray-900 group-hover:text-blue-700 transition">{{ $cabinet->nom }}</p>
                                <span class="text-xs px-1.5 py-0.5 rounded-full shrink-0 {{ $hasCreneaux ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $hasCreneaux ? 'Dispo' : 'Indispo' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $cabinet->ville }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $cabinet->opticiens_count }} opticien(s)</p>
                        </div>

                        <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 transition shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</x-patient-layout>
