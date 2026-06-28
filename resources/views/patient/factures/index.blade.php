<x-patient-layout title="Mes factures">

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-900">Mes factures</h2>
        <p class="text-sm text-gray-500">{{ $factures->total() }} facture(s)</p>
    </div>
</div>

@php
    $statutColors = [
        'brouillon' => 'bg-gray-100 text-gray-600',
        'emise'     => 'bg-blue-100 text-blue-700',
        'payee'     => 'bg-green-100 text-green-700',
        'annulee'   => 'bg-red-100 text-red-700',
        'remboursee'=> 'bg-purple-100 text-purple-700',
    ];
    $statutLabels = [
        'brouillon'  => 'Brouillon',
        'emise'      => 'À payer',
        'payee'      => 'Payée',
        'annulee'    => 'Annulée',
        'remboursee' => 'Remboursée',
    ];
@endphp

@if($factures->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-medium">Aucune facture</p>
        <p class="text-sm text-gray-400 mt-1">Vos factures apparaîtront ici après chaque commande.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($factures as $facture)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl {{ $facture->statut === 'payee' ? 'bg-green-100' : 'bg-blue-50' }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 {{ $facture->statut === 'payee' ? 'text-green-600' : 'text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $facture->numero }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statutColors[$facture->statut] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statutLabels[$facture->statut] ?? $facture->statut }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $facture->cabinet->nom }}
                            @if($facture->commande)
                                · Commande {{ $facture->commande->numero }}
                            @endif
                            · Émise le {{ $facture->date_emission->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <p class="text-lg font-bold text-gray-900">
                        {{ number_format($facture->montant_ttc, 0, ',', ' ') }} XAF
                    </p>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('patient.factures.show', $facture) }}"
                           class="px-3 py-1.5 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                            Détail
                        </a>
                        @if($facture->statut === 'emise')
                            <a href="{{ route('patient.factures.show', $facture) }}#payer"
                               class="px-3 py-1.5 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Payer
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($factures->hasPages())
        <div class="mt-4">{{ $factures->links() }}</div>
    @endif
@endif

</x-patient-layout>
