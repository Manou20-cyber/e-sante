<x-admin-layout title="Commandes">

@php $isSuperAdmin = auth()->user()->hasRole('super_admin'); @endphp

<div x-data="{
    editItem: {},
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-commande') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-commande') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Commandes</h2>
            <p class="text-sm text-gray-500">{{ $commandes->total() }} commande(s)</p>
        </div>
        @unless($isSuperAdmin)
            <button @click="$dispatch('open-modal', 'create-commande')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle commande
            </button>
        @else
            <span class="text-xs px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg font-medium">Vue lecture seule</span>
        @endunless
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Numéro</th>
                        <th class="px-6 py-3 text-left font-medium">Patient</th>
                        <th class="px-6 py-3 text-left font-medium">Cabinet</th>
                        <th class="px-6 py-3 text-left font-medium">Montant</th>
                        <th class="px-6 py-3 text-left font-medium">Statut</th>
                        <th class="px-6 py-3 text-left font-medium">Paiement</th>
                        <th class="px-6 py-3 text-left font-medium">Date</th>
                        @unless($isSuperAdmin)
                            <th class="px-6 py-3 text-left font-medium">Actions</th>
                        @endunless
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($commandes as $commande)
                        @php
                            $sc = [
                                'en_attente' => 'bg-yellow-100 text-yellow-700',
                                'confirmee' => 'bg-blue-100 text-blue-700',
                                'en_preparation' => 'bg-purple-100 text-purple-700',
                                'prete' => 'bg-teal-100 text-teal-700',
                                'livree' => 'bg-green-100 text-green-700',
                                'annulee' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-mono text-xs text-gray-700">{{ $commande->numero }}</td>
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $commande->patient->user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $commande->cabinet->nom }}</td>
                            <td class="px-6 py-3 font-medium text-gray-900">{{ number_format($commande->montant_total, 0, ",", " ") }} XAF</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$commande->statut] ?? '' }}">
                                    {{ str_replace('_', ' ', $commande->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if($commande->facture?->statut === 'payee')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0"></span>
                                        Payée
                                    </span>
                                @elseif($commande->facture)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                        En attente
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $commande->created_at->format('d/m/Y') }}</td>
                            @unless($isSuperAdmin)
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <button @click="setEdit({{ $commande }})"
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition"
                                                title="Modifier le statut">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button @click="setDelete({{ $commande->id }})"
                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endunless
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isSuperAdmin ? 7 : 8 }}" class="px-6 py-10 text-center text-gray-400">Aucune commande</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($commandes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $commandes->links() }}</div>
        @endif
    </div>

    @unless($isSuperAdmin)
    @php $selectClass = 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'; @endphp

    {{-- Modal Créer --}}
    <x-modal name="create-commande" max-width="lg">
        <form method="POST" action="{{ route('admin.commandes.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouvelle commande</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="cmd_patient" value="Patient *"/>
                    <select id="cmd_patient" name="patient_id" required class="{{ $selectClass }}">
                        <option value="">-- Sélectionner --</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="cmd_cabinet" value="Cabinet *"/>
                    <select id="cmd_cabinet" name="cabinet_id" required class="{{ $selectClass }}">
                        <option value="">-- Sélectionner --</option>
                        @foreach($cabinets as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label value="Produits *"/>
                    <div class="mt-1 border border-gray-200 rounded-md divide-y divide-gray-100 max-h-48 overflow-y-auto">
                        @foreach($produits as $produit)
                            <label class="flex items-center gap-3 px-3 py-2 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="produits[{{ $loop->index }}][id]"
                                       value="{{ $produit->id }}"
                                       class="rounded border-gray-300 text-blue-600"/>
                                <span class="flex-1 text-sm">{{ $produit->libelle }}</span>
                                <span class="text-xs text-gray-500">{{ number_format($produit->prix, 0, ",", " ") }} XAF</span>
                                <input type="number" name="produits[{{ $loop->index }}][quantite]"
                                       value="1" min="1"
                                       class="w-16 text-xs border-gray-300 rounded text-center"/>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="cmd_adresse" value="Adresse de livraison"/>
                    <x-text-input id="cmd_adresse" name="adresse_livraison" class="mt-1 block w-full"/>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-commande')">Annuler</x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier (statut seulement) --}}
    <x-modal name="edit-commande" max-width="sm">
        <form method="POST" :action="`{{ url('dashboard/commandes') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier le statut</h2>
            <div class="space-y-4">
                <div>
                    <x-input-label value="Statut *"/>
                    <select name="statut" required x-model="editItem.statut" class="{{ $selectClass }}">
                        <option value="en_attente">En attente</option>
                        <option value="confirmee">Confirmée</option>
                        <option value="en_preparation">En préparation</option>
                        <option value="prete">Prête</option>
                        <option value="livree">Livrée</option>
                        <option value="annulee">Annulée</option>
                    </select>
                </div>
                <template x-if="editItem.notes">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Note du client</p>
                        <div class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-700 italic" x-text="editItem.notes"></div>
                    </div>
                </template>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-commande')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-commande" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer la commande</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
            <form method="POST" :action="`{{ url('dashboard/commandes') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-commande')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

    @endunless

</div>
</x-admin-layout>
