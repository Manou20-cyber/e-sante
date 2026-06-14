<x-admin-layout title="Statistiques & rapports">

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="space-y-8">

    {{-- ═══ KPI RENDEZ-VOUS & SOINS ═══ --}}
    <div>
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Rendez-vous & soins</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $kpiCards = [
                    ['Rendez-vous ce mois', $kpis['rdv_mois'], 'bg-blue-50', 'text-blue-600', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['RDV aujourd\'hui', $kpis['rdv_aujourd_hui'], 'bg-indigo-50', 'text-indigo-600', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Consultations ce mois', $kpis['consultations_mois'], 'bg-teal-50', 'text-teal-600', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['Patients totaux', $kpis['patients_total'], 'bg-purple-50', 'text-purple-600', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['Examens optiques', $kpis['examens_total'], 'bg-orange-50', 'text-orange-600', 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                    ['RDV total', $kpis['rdv_total'], 'bg-gray-50', 'text-gray-600', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['Cabinets actifs', $kpis['cabinets_actifs'], 'bg-red-50', 'text-red-500', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['CA consultations (mois)', number_format($kpis['ca_mois'], 0, ',', ' ').' XAF', 'bg-green-50', 'text-green-600', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp

            @foreach($kpiCards as [$label, $value, $bg, $color, $icon])
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                    <div class="w-10 h-10 {{ $bg }} rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="text-lg font-bold text-gray-900">{{ $value }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ KPI COMMANDES & CA ═══ --}}
    <div>
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Commandes & chiffre d'affaires</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $kpiCommandes = [
                    ['Commandes ce mois', $kpis['commandes_mois'], 'bg-blue-50', 'text-blue-600', 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    ['En attente traitement', $kpis['commandes_en_attente'], 'bg-amber-50', 'text-amber-600', 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Factures en attente', $kpis['factures_en_attente'], 'bg-orange-50', 'text-orange-600', 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
                    ['Factures payées', $kpis['factures_payees'], 'bg-green-50', 'text-green-600', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['CA commandes (mois)', number_format($kpis['ca_commandes_mois'], 0, ',', ' ').' XAF', 'bg-violet-50', 'text-violet-600', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['CA total (mois)', number_format($kpis['ca_total_mois'], 0, ',', ' ').' XAF', 'bg-emerald-50', 'text-emerald-600', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Panier moyen', number_format($kpis['panier_moyen'], 0, ',', ' ').' XAF', 'bg-teal-50', 'text-teal-600', 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                ];
            @endphp

            @foreach($kpiCommandes as [$label, $value, $bg, $color, $icon])
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                    <div class="w-10 h-10 {{ $bg }} rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="text-lg font-bold text-gray-900">{{ $value }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ RÉCAP CA — tableau mois / année ═══ --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Récapitulatif chiffre d'affaires</h3>
            <span class="text-xs text-gray-400">{{ now()->translatedFormat('F Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Source</th>
                        <th class="px-6 py-3 text-right font-medium">Ce mois</th>
                        <th class="px-6 py-3 text-right font-medium">Cette année</th>
                        <th class="px-6 py-3 text-right font-medium">% du total annuel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-teal-500 shrink-0"></span>
                                <span class="font-medium text-gray-800">Consultations</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-right font-mono text-gray-900">{{ number_format($caRecap['ca_consult_mois'], 0, ',', ' ') }} XAF</td>
                        <td class="px-6 py-3 text-right font-mono text-gray-900">{{ number_format($caRecap['ca_consult_annee'], 0, ',', ' ') }} XAF</td>
                        <td class="px-6 py-3 text-right">
                            @php $pctConsult = $caRecap['ca_total_annee'] > 0 ? round($caRecap['ca_consult_annee'] / $caRecap['ca_total_annee'] * 100) : 0; @endphp
                            <div class="flex items-center gap-2 justify-end">
                                <div class="w-20 bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $pctConsult }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 w-8 text-right">{{ $pctConsult }}%</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-violet-500 shrink-0"></span>
                                <span class="font-medium text-gray-800">Commandes</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-right font-mono text-gray-900">{{ number_format($caRecap['ca_commandes_mois'], 0, ',', ' ') }} XAF</td>
                        <td class="px-6 py-3 text-right font-mono text-gray-900">{{ number_format($caRecap['ca_commandes_annee'], 0, ',', ' ') }} XAF</td>
                        <td class="px-6 py-3 text-right">
                            @php $pctCommandes = $caRecap['ca_total_annee'] > 0 ? round($caRecap['ca_commandes_annee'] / $caRecap['ca_total_annee'] * 100) : 0; @endphp
                            <div class="flex items-center gap-2 justify-end">
                                <div class="w-20 bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-violet-500 h-1.5 rounded-full" style="width: {{ $pctCommandes }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 w-8 text-right">{{ $pctCommandes }}%</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-gray-50 font-semibold">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                <span class="text-gray-900">Total</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-right font-mono text-gray-900">{{ number_format($caRecap['ca_total_mois'], 0, ',', ' ') }} XAF</td>
                        <td class="px-6 py-3 text-right font-mono text-gray-900">{{ number_format($caRecap['ca_total_annee'], 0, ',', ' ') }} XAF</td>
                        <td class="px-6 py-3 text-right text-xs text-gray-400">100 %</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══ GRAPHIQUES RDV & PATIENTS ═══ --}}
    <div>
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Rendez-vous & patients</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Rendez-vous par mois</h3>
                <canvas id="chartRdvMois" height="200"></canvas>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Nouveaux patients par mois</h3>
                <canvas id="chartPatientsMois" height="200"></canvas>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Répartition des types de RDV</h3>
                @if(count($rdvParType['labels']) > 0)
                    <canvas id="chartRdvType" height="200"></canvas>
                @else
                    <p class="text-sm text-gray-400 text-center py-10">Aucune donnée</p>
                @endif
            </div>

        </div>
    </div>

    {{-- ═══ GRAPHIQUES CA & COMMANDES ═══ --}}
    <div>
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Chiffre d'affaires & commandes</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- CA total par mois — stacked bar --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 lg:col-span-2">
                <div class="flex items-center gap-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">CA total par mois (XAF)</h3>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-teal-400 inline-block"></span>Consultations</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-violet-400 inline-block"></span>Commandes</span>
                    </div>
                </div>
                <canvas id="chartCaTotal" height="120"></canvas>
            </div>

            {{-- Commandes par mois --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Commandes par mois</h3>
                <canvas id="chartCommandesMois" height="200"></canvas>
            </div>

            {{-- Répartition statuts commandes --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Répartition des statuts commandes</h3>
                @if(count($commandesParStatut['labels']) > 0)
                    <canvas id="chartCommandesStatut" height="200"></canvas>
                @else
                    <p class="text-sm text-gray-400 text-center py-10">Aucune commande</p>
                @endif
            </div>

        </div>
    </div>

    {{-- ═══ TOP PRODUITS ═══ --}}
    @if($topProduits->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Top 5 produits — volume commandé</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($topProduits as $i => $prod)
                    <div class="flex items-center gap-4 px-5 py-3">
                        <span class="w-7 h-7 rounded-full bg-violet-100 text-violet-700 text-xs font-bold flex items-center justify-center shrink-0">
                            {{ $i + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $prod->libelle }}</p>
                            <p class="text-xs text-gray-400">{{ $prod->categorie }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-semibold text-gray-900">{{ number_format($prod->ca_total, 0, ',', ' ') }} XAF</p>
                            <p class="text-xs text-gray-400">{{ $prod->quantite_totale }} unité(s)</p>
                        </div>
                        <div class="w-24 bg-gray-100 rounded-full h-1.5 shrink-0">
                            <div class="bg-violet-500 h-1.5 rounded-full"
                                 style="width: {{ $topProduits->max('quantite_totale') > 0 ? round($prod->quantite_totale / $topProduits->max('quantite_totale') * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ═══ TOP OPTICIENS ═══ --}}
    @if($topOpticiens->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Top 5 opticiens — nombre de RDV</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($topOpticiens as $i => $row)
                    <div class="flex items-center gap-4 px-5 py-3">
                        <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                            {{ $i + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $row->opticien?->name ?? '—' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-32 bg-gray-100 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full"
                                     style="width: {{ $topOpticiens->max('total') > 0 ? round($row->total / $topOpticiens->max('total') * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 w-8 text-right">{{ $row->total }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
    const palette = ['#3b82f6','#6366f1','#14b8a6','#22c55e','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#f97316','#84cc16','#a78bfa'];

    function makeLineChart(id, labels, data, label, color) {
        const ctx = document.getElementById(id);
        if (!ctx) { return; }
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label,
                    data,
                    borderColor: color,
                    backgroundColor: color + '22',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false }, ticks: { maxRotation: 45, font: { size: 10 } } }
                }
            }
        });
    }

    // RDV & Patients
    makeLineChart('chartRdvMois', @json($rdvParMois['labels']), @json($rdvParMois['data']), 'Rendez-vous', '#3b82f6');
    makeLineChart('chartPatientsMois', @json($patientsParMois['labels']), @json($patientsParMois['data']), 'Nouveaux patients', '#8b5cf6');

    @if(count($rdvParType['labels']) > 0)
    new Chart(document.getElementById('chartRdvType'), {
        type: 'doughnut',
        data: {
            labels: @json($rdvParType['labels']),
            datasets: [{
                data: @json($rdvParType['data']),
                backgroundColor: palette.slice(0, {{ count($rdvParType['labels']) }}),
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } } }
        }
    });
    @endif

    // CA total — stacked bar (consultations + commandes)
    new Chart(document.getElementById('chartCaTotal'), {
        type: 'bar',
        data: {
            labels: @json($caParMois['labels']),
            datasets: [
                {
                    label: 'Consultations',
                    data: @json($caParMois['data']),
                    backgroundColor: '#2dd4bf99',
                    borderColor: '#14b8a6',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Commandes',
                    data: @json($caCommandesParMois['data']),
                    backgroundColor: '#a78bfa99',
                    borderColor: '#8b5cf6',
                    borderWidth: 1,
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ' : ' + ctx.parsed.y.toLocaleString('fr-FR') + ' XAF'
                    }
                }
            },
            scales: {
                x: { stacked: true, grid: { display: false }, ticks: { maxRotation: 45, font: { size: 10 } } },
                y: { stacked: true, beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: v => v.toLocaleString('fr-FR') } }
            }
        }
    });

    // Commandes par mois
    makeLineChart('chartCommandesMois', @json($commandesParMois['labels']), @json($commandesParMois['data']), 'Commandes', '#8b5cf6');

    // Répartition statuts commandes — doughnut
    @if(count($commandesParStatut['labels']) > 0)
    new Chart(document.getElementById('chartCommandesStatut'), {
        type: 'doughnut',
        data: {
            labels: @json($commandesParStatut['labels']),
            datasets: [{
                data: @json($commandesParStatut['data']),
                backgroundColor: @json($commandesParStatut['colors']),
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } } }
        }
    });
    @endif
</script>

</x-admin-layout>
