<x-patient-layout title="Mes commandes">

<div x-data="{ retourCommande: null, setRetour(id) { this.retourCommande = id; $dispatch('open-modal', 'retour-modal') } }">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Mes commandes</h2>
            <p class="text-sm text-gray-500">{{ $commandes->total() }} commande(s)</p>
        </div>
        <a href="{{ route('patient.commandes.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Passer une commande
        </a>
    </div>

    @if($commandes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <p class="text-gray-600 font-medium">Aucune commande</p>
            <p class="text-sm text-gray-400 mt-1">Passez votre première commande de lunettes.</p>
            <a href="{{ route('patient.commandes.create') }}"
               class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                Passer une commande
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($commandes as $commande)
                @php
                    $etapes = ['en_attente', 'confirmee', 'en_preparation', 'prete', 'livree'];
                    $etapeIndex = array_search($commande->statut, $etapes);
                    $etapeIndex = $etapeIndex === false ? -1 : $etapeIndex;

                    $labels = [
                        'en_attente' => 'En attente',
                        'confirmee' => 'Confirmée',
                        'en_preparation' => 'En préparation',
                        'prete' => 'Prête',
                        'livree' => 'Livrée',
                        'annulee' => 'Annulée',
                    ];

                    $sc = [
                        'en_attente' => 'bg-yellow-100 text-yellow-700',
                        'confirmee' => 'bg-blue-100 text-blue-700',
                        'en_preparation' => 'bg-purple-100 text-purple-700',
                        'prete' => 'bg-teal-100 text-teal-700',
                        'livree' => 'bg-green-100 text-green-700',
                        'annulee' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {{-- En-tête commande --}}
                    <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-gray-50">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $commande->numero }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $sc[$commande->statut] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $labels[$commande->statut] ?? $commande->statut }}
                            </span>
                            @if($commande->retour)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-medium">
                                    Retour {{ strtolower($labels[$commande->retour->statut] ?? $commande->retour->statut) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 flex-wrap justify-end">
                            <p class="font-semibold text-gray-900">{{ number_format($commande->montant_total, 0, ',', ' ') }} XAF</p>
                            @if($commande->facture?->statut === 'payee')
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Payée
                                </span>
                            @elseif($commande->facture)
                                <a href="{{ route('patient.factures.show', $commande->facture) }}#payer"
                                   class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-500 text-white hover:bg-amber-600 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Paiement requis
                                </a>
                            @endif
                            @if($commande->statut === 'livree' && !$commande->retour)
                                <button @click="setRetour({{ $commande->id }})"
                                        class="text-xs px-3 py-1.5 border border-orange-200 text-orange-600 rounded-lg hover:bg-orange-50 transition">
                                    Retourner
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Timeline de progression --}}
                    @if($commande->statut !== 'annulee')
                        <div class="px-5 py-4 border-b border-gray-50">
                            <div class="flex items-start gap-0">
                                @foreach($etapes as $i => $etape)
                                    <div class="flex-1 flex flex-col items-center gap-1.5">
                                        <div class="w-full flex items-center">
                                            @if($i > 0)
                                                <div class="flex-1 h-0.5 {{ $i <= $etapeIndex ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                            @endif
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0
                                                {{ $i < $etapeIndex ? 'bg-blue-500' : ($i === $etapeIndex ? 'bg-blue-600 ring-4 ring-blue-100' : 'bg-gray-200') }}">
                                                @if($i < $etapeIndex)
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @elseif($i === $etapeIndex)
                                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                                @endif
                                            </div>
                                            @if($i < count($etapes) - 1)
                                                <div class="flex-1 h-0.5 {{ $i < $etapeIndex ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                            @endif
                                        </div>
                                        <span class="text-xs {{ $i === $etapeIndex ? 'text-blue-600 font-semibold' : ($i < $etapeIndex ? 'text-gray-500' : 'text-gray-300') }} text-center leading-tight hidden sm:block">
                                            {{ $labels[$etape] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Détails --}}
                    <div class="px-5 py-3">
                        <p class="text-xs text-gray-500 mb-2">
                            {{ $commande->cabinet->nom }}
                            <span class="text-gray-300 mx-1">·</span>
                            Commandé le {{ $commande->created_at->format('d/m/Y') }}
                        </p>
                        @if($commande->produits->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($commande->produits as $produit)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                        {{ $produit->libelle }} ×{{ $produit->pivot->quantite }}
                                        <span class="text-gray-400 ml-1">{{ number_format($produit->pivot->prix_unitaire, 0, ',', ' ') }} XAF</span>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if($commande->adresse_livraison)
                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $commande->adresse_livraison }}
                            </p>
                        @endif
                    </div>

                    {{-- Détail retour (si existant) --}}
                    @if($commande->retour)
                        @php
                            $retourColors = [
                                'en_attente' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'title' => 'text-orange-700', 'badge' => 'bg-orange-100 text-orange-700'],
                                'approuve'   => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'title' => 'text-blue-700',   'badge' => 'bg-blue-100 text-blue-700'],
                                'refuse'     => ['bg' => 'bg-red-50',    'border' => 'border-red-200',    'title' => 'text-red-700',    'badge' => 'bg-red-100 text-red-700'],
                                'traite'     => ['bg' => 'bg-green-50',  'border' => 'border-green-200',  'title' => 'text-green-700',  'badge' => 'bg-green-100 text-green-700'],
                            ];
                            $retourLabels = ['en_attente' => 'En attente de traitement', 'approuve' => 'Retour approuvé', 'refuse' => 'Retour refusé', 'traite' => 'Retour traité'];
                            $rc = $retourColors[$commande->retour->statut] ?? $retourColors['en_attente'];
                        @endphp
                        <div class="mx-5 mb-4 rounded-xl border {{ $rc['bg'] }} {{ $rc['border'] }} p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 {{ $rc['title'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    <span class="text-xs font-semibold {{ $rc['title'] }}">Demande de retour</span>
                                </div>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $rc['badge'] }}">
                                    {{ $retourLabels[$commande->retour->statut] }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-600 mb-1">
                                <span class="font-medium text-gray-700">Votre raison :</span>
                                {{ $commande->retour->raison }}
                            </p>

                            @if($commande->retour->notes_cabinet)
                                <div class="mt-2 pt-2 border-t border-current/20">
                                    <p class="text-xs font-medium text-gray-700 mb-0.5">Réponse du cabinet :</p>
                                    <p class="text-xs text-gray-600">{{ $commande->retour->notes_cabinet }}</p>
                                </div>
                            @endif

                            @if($commande->retour->montant_rembourse !== null)
                                <div class="mt-2 pt-2 border-t border-current/20 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-green-700">
                                        Remboursement : {{ number_format($commande->retour->montant_rembourse, 0, ',', ' ') }} XAF
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($commandes->hasPages())
            <div class="mt-4">{{ $commandes->links() }}</div>
        @endif
    @endif

    {{-- Modal retour --}}
    <x-modal name="retour-modal" max-width="md">
        <form method="POST" :action="`{{ url('patient/commandes') }}/${retourCommande}/retour`" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Demande de retour</h2>
            <p class="text-sm text-gray-500 mb-5">Expliquez la raison de votre retour. Le cabinet vous contactera pour la suite.</p>
            <div>
                <x-input-label for="raison" value="Raison du retour * (minimum 20 caractères)"/>
                <textarea id="raison" name="raison" rows="4" required minlength="20"
                          class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                          placeholder="Décrivez le problème avec la commande..."></textarea>
                <x-input-error :messages="$errors->get('raison')" class="mt-1"/>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'retour-modal')">Annuler</x-secondary-button>
                <x-primary-button class="bg-orange-600 hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900">
                    Envoyer la demande
                </x-primary-button>
            </div>
        </form>
    </x-modal>

</div>
</x-patient-layout>
