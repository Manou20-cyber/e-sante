<x-patient-layout title="Choisir un opticien">

    <div class="mb-4">
        <a href="{{ route('patient.cabinets.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux cabinets
        </a>
    </div>

    {{-- Header cabinet --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white mb-6">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold">{{ $cabinet->nom }}</h1>
                <p class="text-blue-200 text-sm mt-0.5">{{ $cabinet->adresse }}, {{ $cabinet->ville }}</p>
                <p class="text-blue-300 text-xs mt-1">{{ $cabinet->telephone }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-base font-semibold text-gray-900 mb-4">
        Nos opticiens ({{ $cabinet->opticiens->count() }})
    </h2>

    @if($cabinet->opticiens->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
            <p class="text-gray-500">Aucun opticien disponible dans ce cabinet pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($cabinet->opticiens as $opticien)
                @php
                    $creneauxActifs = $opticien->creneaux->where('est_actif', true);
                    $minPrix = $creneauxActifs->min('prix');
                    $joursDispos = $creneauxActifs->pluck('jour_semaine')->unique()->sort();
                    $joursMap = [1=>'Lun',2=>'Mar',3=>'Mer',4=>'Jeu',5=>'Ven',6=>'Sam',7=>'Dim'];
                @endphp
                <a href="{{ route('patient.cabinets.opticien', [$cabinet, $opticien]) }}"
                   class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group p-5 block">

                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-teal-400 flex items-center justify-center font-bold text-white text-2xl">
                            {{ strtoupper(substr($opticien->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 group-hover:text-blue-700 transition">{{ $opticien->name }}</p>
                            @if($minPrix)
                                <p class="text-sm text-teal-600 font-semibold">
                                    À partir de {{ number_format($minPrix, 0, ',', ' ') }} XAF
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($creneauxActifs->isNotEmpty())
                        <div class="mb-3">
                            <p class="text-xs text-gray-400 mb-2">Disponibilités :</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($joursDispos as $jour)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded-full font-medium">
                                        {{ $joursMap[$jour] ?? '?' }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-xs text-gray-400">{{ $creneauxActifs->count() }} créneau(x) disponible(s)</p>
                    @else
                        <p class="text-xs text-orange-500">Aucun créneau configuré actuellement</p>
                    @endif

                    <div class="mt-4 flex items-center text-xs text-blue-600 font-medium group-hover:translate-x-1 transition-transform">
                        Voir les disponibilités
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</x-patient-layout>
