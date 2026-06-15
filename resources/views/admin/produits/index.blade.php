<x-admin-layout title="Produits / Stock">

@php $isSuperAdmin = auth()->user()->hasRole('super_admin'); @endphp

<div x-data="{
    editItem: {},
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-produit') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-produit') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Produits / Stock</h2>
            <p class="text-sm text-gray-500">{{ $produits->total() }} produit(s)</p>
        </div>
        @unless($isSuperAdmin)
            <button @click="$dispatch('open-modal', 'create-produit')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau produit
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
                        <th class="px-6 py-3 text-left font-medium">Libellé</th>
                        <th class="px-6 py-3 text-left font-medium">Cabinet</th>
                        <th class="px-6 py-3 text-left font-medium">Catégorie</th>
                        <th class="px-6 py-3 text-left font-medium">Prix</th>
                        <th class="px-6 py-3 text-left font-medium">Stock</th>
                        <th class="px-6 py-3 text-left font-medium">Statut</th>
                        @unless($isSuperAdmin)
                            <th class="px-6 py-3 text-left font-medium">Actions</th>
                        @endunless
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($produits as $produit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $produit->libelle }}
                                @if($produit->marque)
                                    <span class="text-xs text-gray-400 ml-1">— {{ $produit->marque }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $produit->cabinet->nom }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $produit->categorie }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-900 font-medium">{{ number_format($produit->prix, 0, ",", " ") }} XAF</td>
                            <td class="px-6 py-3">
                                <span class="{{ $produit->stock <= $produit->stock_alerte ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                                    {{ $produit->stock }}
                                </span>
                                @if($produit->stock <= $produit->stock_alerte)
                                    <span class="ml-1 text-xs text-red-500">⚠ alerte</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $produit->est_actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $produit->est_actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            @unless($isSuperAdmin)
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <button @click="setEdit({{ $produit }})"
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button @click="setDelete({{ $produit->id }})"
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
                            <td colspan="{{ $isSuperAdmin ? 6 : 7 }}" class="px-6 py-10 text-center text-gray-400">Aucun produit</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($produits->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $produits->links() }}</div>
        @endif
    </div>

    @unless($isSuperAdmin)

    {{-- Modal Créer --}}
    <x-modal name="create-produit" max-width="lg">
        <form method="POST" action="{{ route('admin.produits.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouveau produit</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label for="pr_libelle" value="Libellé *"/>
                    <x-text-input id="pr_libelle" name="libelle" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="pr_cabinet" value="Cabinet *"/>
                    <select id="pr_cabinet" name="cabinet_id" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sélectionner --</option>
                        @foreach($cabinets as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="pr_cat" value="Catégorie *"/>
                    <select id="pr_cat" name="categorie" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="monture">Monture</option>
                        <option value="lentille">Lentille</option>
                        <option value="verre">Verre</option>
                        <option value="accessoire">Accessoire</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="pr_prix" value="Prix (XAF) *"/>
                    <x-text-input id="pr_prix" name="prix" type="number" step="0.01" min="0" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="pr_marque" value="Marque"/>
                    <x-text-input id="pr_marque" name="marque" class="mt-1 block w-full"/>
                </div>
                <div>
                    <x-input-label for="pr_stock" value="Stock *"/>
                    <x-text-input id="pr_stock" name="stock" type="number" min="0" value="0" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="pr_alerte" value="Seuil d'alerte *"/>
                    <x-text-input id="pr_alerte" name="stock_alerte" type="number" min="0" value="5" class="mt-1 block w-full" required/>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="pr_ref" value="Référence"/>
                    <x-text-input id="pr_ref" name="reference" class="mt-1 block w-full"/>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-produit')">Annuler</x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-produit" max-width="lg">
        <form method="POST" :action="`{{ url('dashboard/produits') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier le produit</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label value="Libellé *"/>
                    <x-text-input name="libelle" class="mt-1 block w-full" x-model="editItem.libelle" required/>
                </div>
                <div>
                    <x-input-label value="Cabinet *"/>
                    <select name="cabinet_id" required x-model="editItem.cabinet_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($cabinets as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Catégorie *"/>
                    <select name="categorie" required x-model="editItem.categorie"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="monture">Monture</option>
                        <option value="lentille">Lentille</option>
                        <option value="verre">Verre</option>
                        <option value="accessoire">Accessoire</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div>
                    <x-input-label value="Prix (XAF) *"/>
                    <x-text-input name="prix" type="number" step="0.01" min="0" class="mt-1 block w-full" x-model="editItem.prix" required/>
                </div>
                <div>
                    <x-input-label value="Marque"/>
                    <x-text-input name="marque" class="mt-1 block w-full" x-model="editItem.marque"/>
                </div>
                <div>
                    <x-input-label value="Stock *"/>
                    <x-text-input name="stock" type="number" min="0" class="mt-1 block w-full" x-model="editItem.stock" required/>
                </div>
                <div>
                    <x-input-label value="Seuil d'alerte *"/>
                    <x-text-input name="stock_alerte" type="number" min="0" class="mt-1 block w-full" x-model="editItem.stock_alerte" required/>
                </div>
                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="hidden" name="est_actif" value="0">
                    <input type="checkbox" id="pr_actif" name="est_actif" value="1"
                           x-bind:checked="editItem?.est_actif"
                           class="rounded border-gray-300 text-blue-600 shadow-sm"/>
                    <label for="pr_actif" class="text-sm text-gray-700">Produit actif</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-produit')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-produit" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer le produit</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
            <form method="POST" :action="`{{ url('dashboard/produits') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-produit')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

    @endunless

</div>
</x-admin-layout>
