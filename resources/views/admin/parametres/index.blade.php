<x-admin-layout title="Paramètres">

<div x-data="{
    editItem: null,
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-parametre') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-parametre') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Paramètres système</h2>
            <p class="text-sm text-gray-500">{{ $parametres->total() }} paramètre(s)</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-parametre')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau paramètre
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Clé</th>
                        <th class="px-6 py-3 text-left font-medium">Valeur</th>
                        <th class="px-6 py-3 text-left font-medium">Groupe</th>
                        <th class="px-6 py-3 text-left font-medium">Description</th>
                        <th class="px-6 py-3 text-left font-medium">Public</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($parametres as $parametre)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-mono text-xs text-blue-700">{{ $parametre->cle }}</td>
                            <td class="px-6 py-3 text-gray-700 max-w-xs truncate">{{ $parametre->valeur ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $parametre->groupe }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs max-w-xs truncate">{{ $parametre->description ?? '-' }}</td>
                            <td class="px-6 py-3">
                                @if($parametre->est_public)
                                    <span class="text-green-600 text-xs font-medium">Oui</span>
                                @else
                                    <span class="text-gray-400 text-xs">Non</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="setEdit({{ $parametre }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="setDelete({{ $parametre->id }})"
                                            class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Aucun paramètre configuré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($parametres->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $parametres->links() }}</div>
        @endif
    </div>

    {{-- Modal Créer --}}
    <x-modal name="create-parametre" max-width="lg">
        <form method="POST" action="{{ route('admin.parametres.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouveau paramètre</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="pm_cle" value="Clé *"/>
                        <x-text-input id="pm_cle" name="cle" placeholder="ex: app.nom" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label for="pm_groupe" value="Groupe *"/>
                        <x-text-input id="pm_groupe" name="groupe" value="general" class="mt-1 block w-full" required/>
                    </div>
                </div>
                <div>
                    <x-input-label for="pm_valeur" value="Valeur"/>
                    <textarea id="pm_valeur" name="valeur" rows="2"
                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                </div>
                <div>
                    <x-input-label for="pm_desc" value="Description"/>
                    <x-text-input id="pm_desc" name="description" class="mt-1 block w-full"/>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="est_public" value="0">
                    <input type="checkbox" id="pm_public" name="est_public" value="1"
                           class="rounded border-gray-300 text-blue-600 shadow-sm"/>
                    <label for="pm_public" class="text-sm text-gray-700">Visible publiquement</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-parametre')">Annuler</x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-parametre" max-width="lg">
        <form method="POST" :action="`{{ url('admin/parametres') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier le paramètre</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Clé *"/>
                        <x-text-input name="cle" class="mt-1 block w-full" x-model="editItem.cle" required/>
                    </div>
                    <div>
                        <x-input-label value="Groupe *"/>
                        <x-text-input name="groupe" class="mt-1 block w-full" x-model="editItem.groupe" required/>
                    </div>
                </div>
                <div>
                    <x-input-label value="Valeur"/>
                    <textarea name="valeur" rows="2" x-model="editItem.valeur"
                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                </div>
                <div>
                    <x-input-label value="Description"/>
                    <x-text-input name="description" class="mt-1 block w-full" x-model="editItem.description"/>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="est_public" value="0">
                    <input type="checkbox" name="est_public" value="1"
                           x-bind:checked="editItem?.est_public"
                           class="rounded border-gray-300 text-blue-600 shadow-sm"/>
                    <span class="text-sm text-gray-700">Visible publiquement</span>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-parametre')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-parametre" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer le paramètre</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
            <form method="POST" :action="`{{ url('admin/parametres') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-parametre')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-admin-layout>
