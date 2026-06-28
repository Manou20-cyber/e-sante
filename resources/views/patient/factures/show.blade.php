<x-patient-layout title="Facture {{ $facture->numero }}">

{{-- Navigation --}}
<div class="mb-5">
    <a href="{{ route('patient.factures.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour aux factures
    </a>
</div>

@php
    $statutColors = ['brouillon'=>'bg-gray-100 text-gray-600','emise'=>'bg-blue-100 text-blue-700','payee'=>'bg-green-100 text-green-700','annulee'=>'bg-red-100 text-red-700','remboursee'=>'bg-purple-100 text-purple-700'];
    $statutLabels = ['brouillon'=>'Brouillon','emise'=>'À payer','payee'=>'Payée','annulee'=>'Annulée','remboursee'=>'Remboursée'];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Facture principale --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- En-tête --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $facture->numero }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $facture->cabinet->nom }}</p>
                </div>
                <span class="shrink-0 text-sm px-3 py-1.5 rounded-full font-medium {{ $statutColors[$facture->statut] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statutLabels[$facture->statut] ?? $facture->statut }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Date d'émission</p>
                    <p class="font-medium text-gray-900">{{ $facture->date_emission->format('d/m/Y') }}</p>
                </div>
                @if($facture->date_echeance)
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Échéance</p>
                        <p class="font-medium {{ $facture->statut === 'emise' && $facture->date_echeance->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $facture->date_echeance->format('d/m/Y') }}
                        </p>
                    </div>
                @endif
                @if($facture->commande)
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Commande</p>
                        <p class="font-medium text-gray-900 font-mono text-xs">{{ $facture->commande->numero }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Détail produits (si commande) --}}
        @if($facture->commande && $facture->commande->produits->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50">
                    <h2 class="font-semibold text-gray-900 text-sm">Détail de la commande</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">Produit</th>
                            <th class="px-6 py-3 text-right font-medium">Qté</th>
                            <th class="px-6 py-3 text-right font-medium">P.U.</th>
                            <th class="px-6 py-3 text-right font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($facture->commande->produits as $produit)
                            <tr>
                                <td class="px-6 py-3 text-gray-900">{{ $produit->libelle }}</td>
                                <td class="px-6 py-3 text-right text-gray-600">{{ $produit->pivot->quantite }}</td>
                                <td class="px-6 py-3 text-right text-gray-600">{{ number_format($produit->pivot->prix_unitaire, 0, ',', ' ') }} XAF</td>
                                <td class="px-6 py-3 text-right font-medium text-gray-900">{{ number_format($produit->pivot->prix_unitaire * $produit->pivot->quantite, 0, ',', ' ') }} XAF</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-semibold text-gray-900">Total TTC</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-900 text-base">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} XAF</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        {{-- Paiements effectués --}}
        @if($facture->paiements->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50">
                    <h2 class="font-semibold text-gray-900 text-sm">Historique des paiements</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($facture->paiements as $paiement)
                        <div class="px-6 py-3 flex items-center justify-between text-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $paiement->methode)) }}</p>
                                    <p class="text-xs text-gray-400">Réf. {{ $paiement->reference }} · {{ $paiement->date_paiement->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <p class="font-semibold text-green-700">{{ number_format($paiement->montant, 0, ',', ' ') }} XAF</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- Panneau paiement --}}
    <div class="space-y-4">

        {{-- Récapitulatif montant --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Montant total</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($facture->montant_ttc, 0, ',', ' ') }}</p>
            <p class="text-sm text-gray-400">XAF</p>
        </div>

        {{-- Formulaire paiement mobile money --}}
        @if($facture->statut === 'emise')
            <div id="payer"
                 x-data="{
                    etape: 'form',
                    operateur: '',
                    telephone: '',
                    payer() {
                        if (!this.operateur || !this.telephone) return;
                        this.etape = 'traitement';
                        setTimeout(() => { this.$refs.payForm.submit(); }, 2800);
                    }
                 }"
                 class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- État : formulaire --}}
                <div x-show="etape === 'form'" class="p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="font-semibold text-gray-900">Payer par Mobile Money</h2>
                    </div>

                    <form x-ref="payForm" method="POST" action="{{ route('patient.factures.payer', $facture) }}" @submit.prevent="payer()">
                        @csrf

                        {{-- Choix opérateur --}}
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Opérateur</p>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <label :class="operateur === 'mtn' ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200'"
                                   class="flex items-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition">
                                <input type="radio" name="operateur" value="mtn" x-model="operateur" class="sr-only"/>
                                <div class="w-8 h-8 rounded-lg bg-yellow-400 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-black text-white leading-none">MTN</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-900">MTN</p>
                                    <p class="text-xs text-gray-400">Mobile Money</p>
                                </div>
                            </label>
                            <label :class="operateur === 'orange' ? 'border-orange-400 bg-orange-50' : 'border-gray-200'"
                                   class="flex items-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition">
                                <input type="radio" name="operateur" value="orange" x-model="operateur" class="sr-only"/>
                                <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-black text-white leading-none">ORG</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-900">Orange</p>
                                    <p class="text-xs text-gray-400">Money</p>
                                </div>
                            </label>
                        </div>

                        {{-- Numéro --}}
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Numéro de téléphone</p>
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden mb-1">
                            <span class="px-3 py-3 text-sm text-gray-500 bg-gray-50 border-r border-gray-200 shrink-0">+237</span>
                            <input type="tel" name="telephone" x-model="telephone"
                                   placeholder="6XXXXXXXX" maxlength="9"
                                   class="flex-1 px-3 py-3 text-sm border-0 focus:ring-0 bg-white"/>
                        </div>
                        @error('telephone')
                            <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                        @enderror
                        @error('operateur')
                            <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                        @enderror

                        {{-- Montant --}}
                        <div class="mt-4 py-3 px-4 bg-gray-50 rounded-xl flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-600">Montant à payer</span>
                            <span class="font-bold text-gray-900">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} XAF</span>
                        </div>

                        <button type="submit"
                                :disabled="!operateur || telephone.length < 9"
                                :class="(!operateur || telephone.length < 9) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-green-700'"
                                class="w-full py-3 bg-green-600 text-white text-sm font-semibold rounded-xl transition">
                            Confirmer le paiement
                        </button>
                    </form>

                    <p class="text-xs text-gray-400 text-center mt-3">
                        Simulation — le paiement sera validé instantanément.
                    </p>
                </div>

                {{-- État : traitement en cours --}}
                <div x-show="etape === 'traitement'" x-cloak class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 relative">
                        <svg class="animate-spin w-16 h-16 text-green-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 mb-1">Traitement en cours…</p>
                    <p class="text-sm text-gray-500">Connexion à l'opérateur Mobile Money</p>
                    <div class="mt-4 flex justify-center gap-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </div>
                </div>

            </div>
        @elseif($facture->statut === 'payee')
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="font-semibold text-green-800">Facture payée</p>
                <p class="text-xs text-green-600 mt-1">
                    Le {{ $facture->paiements->first()?->date_paiement->format('d/m/Y à H:i') }}
                </p>
            </div>
        @endif

    </div>
</div>

</x-patient-layout>
