<x-patient-layout title="Passer une commande">

<div x-data="{
    cabinetId: null,
    panier: {},
    cabinets: {{ Js::from($cabinets->map(fn($c) => ['id' => $c->id, 'nom' => $c->nom, 'ville' => $c->ville, 'produits' => $c->produits->map(fn($p) => ['id' => $p->id, 'libelle' => $p->libelle, 'reference' => $p->reference, 'prix' => $p->prix, 'categorie' => $p->categorie, 'marque' => $p->marque, 'stock' => $p->stock])])) }},
    get cabinetCourant() {
        return this.cabinets.find(c => c.id == this.cabinetId) ?? null;
    },
    get produits() {
        return this.cabinetCourant?.produits ?? [];
    },
    get total() {
        return Object.entries(this.panier).reduce((sum, [id, qte]) => {
            const p = this.produits.find(p => p.id == id);
            return p ? sum + p.prix * qte : sum;
        }, 0);
    },
    get lignesPanier() {
        return Object.entries(this.panier)
            .filter(([id, qte]) => qte > 0)
            .map(([id, qte]) => ({ id: parseInt(id), qte }));
    },
    setQte(id, qte) {
        const q = parseInt(qte) || 0;
        if (q <= 0) { delete this.panier[id]; } else { this.panier[id] = q; }
        this.panier = { ...this.panier };
    },
    qte(id) { return this.panier[id] ?? 0; },
    selectCabinet(id) { this.cabinetId = id; this.panier = {}; },
    formatPrix(prix) { return new Intl.NumberFormat('fr-FR').format(prix) + ' XAF'; }
}"
     x-init="cabinetId = {{ old('cabinet_id', 'null') }}">

    {{-- En-tête --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('patient.commandes.index') }}"
           class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Passer une commande</h2>
            <p class="text-sm text-gray-500">Sélectionnez un cabinet et vos lunettes</p>
        </div>
    </div>

    @if($errors->isNotEmpty())
        <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($cabinets->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-600 font-medium">Aucun cabinet disponible</p>
            <p class="text-sm text-gray-400 mt-1">Commencez par prendre un rendez-vous dans un cabinet.</p>
            <a href="{{ route('patient.cabinets.index') }}"
               class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                Trouver un cabinet
            </a>
        </div>
    @else
        <form method="POST" action="{{ route('patient.commandes.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Colonne principale --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Étape 1 : Cabinet --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-semibold text-gray-800 text-sm mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">1</span>
                            Choisir le cabinet
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($cabinets as $cabinet)
                                <label class="cursor-pointer">
                                    <input type="radio" name="cabinet_id" value="{{ $cabinet->id }}"
                                           x-model="cabinetId"
                                           @change="selectCabinet({{ $cabinet->id }})"
                                           class="sr-only">
                                    <div :class="cabinetId == {{ $cabinet->id }} ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500' : 'border-gray-200 hover:border-gray-300'"
                                         class="border rounded-xl p-4 transition">
                                        <p class="font-medium text-gray-900 text-sm">{{ $cabinet->nom }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $cabinet->ville }}</p>
                                        <p class="text-xs text-blue-600 mt-1.5 font-medium">{{ $cabinet->produits->count() }} produit(s) disponible(s)</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('cabinet_id')" class="mt-2"/>
                    </div>

                    {{-- Étape 2 : Produits --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-semibold text-gray-800 text-sm mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">2</span>
                            Sélectionner les produits
                        </h3>

                        <div x-show="!cabinetId" class="text-sm text-gray-400 py-6 text-center">
                            Sélectionnez d'abord un cabinet ci-dessus.
                        </div>

                        <div x-show="cabinetId && produits.length === 0" class="text-sm text-gray-400 py-6 text-center">
                            Ce cabinet n'a pas de produits disponibles actuellement.
                        </div>

                        <div x-show="cabinetId && produits.length > 0" class="space-y-3">
                            <template x-for="produit in produits" :key="produit.id">
                                <div class="flex items-center gap-4 p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 text-sm" x-text="produit.libelle"></p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <span x-text="produit.marque ?? ''"></span>
                                            <span x-show="produit.reference"> — Réf. <span x-text="produit.reference"></span></span>
                                        </p>
                                        <p class="text-xs text-blue-600 font-semibold mt-1" x-text="formatPrix(produit.prix)"></p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button type="button"
                                                @click="setQte(produit.id, qte(produit.id) - 1)"
                                                class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition"
                                                :disabled="qte(produit.id) === 0">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <span class="w-8 text-center text-sm font-semibold text-gray-900" x-text="qte(produit.id)"></span>
                                        <button type="button"
                                                @click="setQte(produit.id, qte(produit.id) + 1)"
                                                :disabled="qte(produit.id) >= produit.stock"
                                                class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-700 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            {{-- Champs cachés pour le panier --}}
                            <template x-for="(ligne, i) in lignesPanier" :key="ligne.id">
                                <span>
                                    <input type="hidden" :name="`produits[${i}][id]`" :value="ligne.id">
                                    <input type="hidden" :name="`produits[${i}][quantite]`" :value="ligne.qte">
                                </span>
                            </template>
                        </div>
                        <x-input-error :messages="$errors->get('produits')" class="mt-2"/>
                    </div>

                    {{-- Étape 3 : Infos supplémentaires --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-semibold text-gray-800 text-sm mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">3</span>
                            Informations de livraison
                        </h3>

                        <div class="space-y-4">
                            @if($ordonnances->isNotEmpty())
                                <div>
                                    <x-input-label for="ordonnance_id" value="Ordonnance associée (optionnel)"/>
                                    <select id="ordonnance_id" name="ordonnance_id"
                                            class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">— Aucune —</option>
                                        @foreach($ordonnances as $ord)
                                            <option value="{{ $ord->id }}" {{ old('ordonnance_id') == $ord->id ? 'selected' : '' }}>
                                                Ordonnance du {{ $ord->date->format('d/m/Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div>
                                <x-input-label for="adresse_livraison" value="Adresse de livraison (optionnel)"/>
                                <textarea id="adresse_livraison" name="adresse_livraison" rows="2"
                                          class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                                          placeholder="Laissez vide pour retrait en cabinet">{{ old('adresse_livraison') }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="notes" value="Notes (optionnel)"/>
                                <textarea id="notes" name="notes" rows="2"
                                          class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                                          placeholder="Instructions spéciales, préférences...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Récapitulatif --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-24">
                        <h3 class="font-semibold text-gray-800 text-sm mb-4">Récapitulatif</h3>

                        <div x-show="lignesPanier.length === 0" class="text-sm text-gray-400 py-4 text-center">
                            Votre panier est vide
                        </div>

                        <div x-show="lignesPanier.length > 0" class="space-y-2 mb-4">
                            <template x-for="ligne in lignesPanier" :key="ligne.id">
                                @php $dummy = null; @endphp
                                <div class="flex justify-between text-sm" x-data>
                                    <span class="text-gray-700 truncate" x-text="produits.find(p => p.id == ligne.id)?.libelle + ' ×' + ligne.qte"></span>
                                    <span class="font-medium text-gray-900 shrink-0 ml-2"
                                          x-text="formatPrix((produits.find(p => p.id == ligne.id)?.prix ?? 0) * ligne.qte)"></span>
                                </div>
                            </template>
                        </div>

                        <div x-show="lignesPanier.length > 0" class="border-t border-gray-100 pt-4 mb-5">
                            <div class="flex justify-between font-semibold text-gray-900">
                                <span>Total</span>
                                <span x-text="formatPrix(total)"></span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Prix indicatif, confirmé par le cabinet</p>
                        </div>

                        <button type="submit"
                                :disabled="!cabinetId || lignesPanier.length === 0"
                                :class="(!cabinetId || lignesPanier.length === 0) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                                class="w-full py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl transition">
                            Passer la commande
                        </button>

                        <a href="{{ route('patient.commandes.index') }}"
                           class="block text-center mt-3 text-sm text-gray-400 hover:text-gray-600 transition">
                            Annuler
                        </a>
                    </div>
                </div>

            </div>
        </form>
    @endif

</div>

</x-patient-layout>
