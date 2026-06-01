<x-admin-layout title="Tableau de bord">

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['patients']) }}</p>
                <p class="text-sm text-gray-500">Patients</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['cabinets']) }}</p>
                <p class="text-sm text-gray-500">Cabinets</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['rendezvous_aujourd_hui']) }}</p>
                <p class="text-sm text-gray-500">RDV aujourd'hui</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['commandes_en_attente']) }}</p>
                <p class="text-sm text-gray-500">Commandes en attente</p>
            </div>
        </div>

    </div>

    {{-- Recent rendez-vous --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Rendez-vous récents</h2>
            <a href="{{ route('admin.rendezvous.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Patient</th>
                        <th class="px-6 py-3 text-left font-medium">Cabinet</th>
                        <th class="px-6 py-3 text-left font-medium">Date</th>
                        <th class="px-6 py-3 text-left font-medium">Type</th>
                        <th class="px-6 py-3 text-left font-medium">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rendezvous_recents as $rdv)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $rdv->patient->user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $rdv->cabinet->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $rdv->date->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $rdv->type }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $colors = [
                                        'en_attente' => 'bg-yellow-100 text-yellow-700',
                                        'confirme' => 'bg-blue-100 text-blue-700',
                                        'termine' => 'bg-green-100 text-green-700',
                                        'annule' => 'bg-red-100 text-red-700',
                                        'absent' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$rdv->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ str_replace('_', ' ', $rdv->statut) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucun rendez-vous</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>
