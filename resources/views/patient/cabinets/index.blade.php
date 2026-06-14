<x-patient-layout title="Choisir un cabinet">

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Cabinets optiques disponibles</h2>
        <p class="text-sm text-gray-500">Sélectionnez un cabinet pour prendre rendez-vous.</p>
    </div>

    @if($cabinets->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-500">Aucun cabinet disponible pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($cabinets as $cabinet)
                @php
                    $opticienCount = $cabinet->opticiens_count;
                    $hasCreneaux = $cabinet->opticiens->some(fn($o) => $o->creneaux->isNotEmpty());
                @endphp
                <a href="{{ route('patient.cabinets.show', $cabinet) }}"
                   class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group overflow-hidden block">

                    {{-- Header coloré --}}
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-teal-400"></div>

                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl shrink-0 overflow-hidden border border-gray-100 bg-blue-50 flex items-center justify-center">
                                @if($cabinet->logo)
                                    <img src="{{ Storage::url($cabinet->logo) }}" alt="{{ $cabinet->nom }}"
                                         class="w-full h-full object-contain p-1">
                                @else
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                @endif
                            </div>
                            @if($hasCreneaux)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-medium">Disponible</span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Pas de créneau</span>
                            @endif
                        </div>

                        <h3 class="font-bold text-gray-900 mb-1 group-hover:text-blue-700 transition">{{ $cabinet->nom }}</h3>
                        <p class="text-xs text-gray-500 mb-3">{{ $cabinet->adresse }}, {{ $cabinet->ville }}</p>

                        <div class="flex items-center gap-4 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $opticienCount }} opticien(s)
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $cabinet->telephone }}
                            </span>
                        </div>

                        @if($cabinet->description)
                            <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ $cabinet->description }}</p>
                        @endif

                        <div class="mt-4 flex items-center text-xs text-blue-600 font-medium group-hover:translate-x-1 transition-transform">
                            Voir les opticiens
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</x-patient-layout>
