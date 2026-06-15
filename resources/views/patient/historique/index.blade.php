<x-patient-layout title="Historique">

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Mon historique</h2>
        <p class="text-sm text-gray-500">{{ $timeline->count() }} événement(s)</p>
    </div>

    @if($timeline->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-gray-600 font-medium">Aucun historique</p>
            <p class="text-sm text-gray-400 mt-1">Votre activité apparaîtra ici au fil du temps.</p>
        </div>
    @else
        <div class="relative">
            {{-- Ligne verticale --}}
            <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gray-200 lg:left-8"></div>

            <div class="space-y-4">
                @foreach($timeline as $event)
                    @php
                        $colorMap = [
                            'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
                            'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
                            'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'border' => 'border-green-200'],
                        ];
                        $colors = $colorMap[$event['couleur']] ?? $colorMap['blue'];

                        $icons = [
                            'rendezvous' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            'commande' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                            'document' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                        ];

                        $statusColors = ['en_attente' => 'bg-yellow-100 text-yellow-700', 'confirme' => 'bg-blue-100 text-blue-700', 'termine' => 'bg-green-100 text-green-700', 'annule' => 'bg-red-100 text-red-700', 'livree' => 'bg-green-100 text-green-700', 'confirmee' => 'bg-blue-100 text-blue-700'];
                    @endphp

                    <div class="relative flex items-start gap-4 pl-12 lg:pl-16">
                        {{-- Icône sur la timeline --}}
                        <div class="absolute left-0 w-10 h-10 lg:w-16 lg:h-10 flex items-center">
                            <div class="w-10 h-10 rounded-xl {{ $colors['bg'] }} {{ $colors['text'] }} flex items-center justify-center shadow-sm border {{ $colors['border'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="{{ $icons[$event['type']] ?? $icons['document'] }}"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $event['titre'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $event['detail'] }}</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($event['statut'])
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColors[$event['statut']] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ str_replace('_', ' ', $event['statut']) }}
                                        </span>
                                    @endif
                                    <p class="text-xs text-gray-400">
                                        @if($event['date'] instanceof \Carbon\Carbon)
                                            {{ $event['date']->format('d/m/Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-patient-layout>
