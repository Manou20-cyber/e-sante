<x-admin-layout title="Cabinets optiques">

<div x-data="{
    editItem: null,
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-cabinet') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-cabinet') }
}">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Cabinets optiques</h2>
            <p class="text-sm text-gray-500">{{ $cabinets->total() }} cabinet(s) enregistré(s)</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-cabinet')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau cabinet
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Nom</th>
                        <th class="px-6 py-3 text-left font-medium">Ville</th>
                        <th class="px-6 py-3 text-left font-medium">Téléphone</th>
                        <th class="px-6 py-3 text-left font-medium">Administrateur</th>
                        <th class="px-6 py-3 text-left font-medium">Statut</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($cabinets as $cabinet)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $cabinet->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $cabinet->ville }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $cabinet->telephone }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $cabinet->admin?->name }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cabinet->est_actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $cabinet->est_actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="setEdit({{ $cabinet }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="setDelete({{ $cabinet->id }})"
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
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Aucun cabinet enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cabinets->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $cabinets->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Créer --}}
    <x-modal name="create-cabinet" max-width="lg">
        <form method="POST" action="{{ route('admin.cabinets.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouveau cabinet</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label for="c_nom" value="Nom du cabinet *"/>
                    <x-text-input id="c_nom" name="nom" class="mt-1 block w-full" required/>
                    <x-input-error :messages="$errors->get('nom')" class="mt-1"/>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="c_adresse" value="Adresse *"/>
                    <x-text-input id="c_adresse" name="adresse" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="c_ville" value="Ville *"/>
                    <x-text-input id="c_ville" name="ville" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="c_cp" value="Code postal *"/>
                    <x-text-input id="c_cp" name="code_postal" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="c_tel" value="Téléphone *"/>
                    <x-text-input id="c_tel" name="telephone" type="tel" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="c_email" value="Email"/>
                    <x-text-input id="c_email" name="email" type="email" class="mt-1 block w-full"/>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="c_admin" value="Administrateur *"/>
                    <select id="c_admin" name="user_id" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sélectionner --</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-cabinet')">
                    Annuler
                </x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-cabinet" max-width="lg">
        <form method="POST" :action="`{{ url('admin/cabinets') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier le cabinet</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label value="Nom du cabinet *"/>
                    <x-text-input name="nom" class="mt-1 block w-full" :value="null" x-model="editItem.nom" required/>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label value="Adresse *"/>
                    <x-text-input name="adresse" class="mt-1 block w-full" x-model="editItem.adresse" required/>
                </div>
                <div>
                    <x-input-label value="Ville *"/>
                    <x-text-input name="ville" class="mt-1 block w-full" x-model="editItem.ville" required/>
                </div>
                <div>
                    <x-input-label value="Code postal *"/>
                    <x-text-input name="code_postal" class="mt-1 block w-full" x-model="editItem.code_postal" required/>
                </div>
                <div>
                    <x-input-label value="Téléphone *"/>
                    <x-text-input name="telephone" type="tel" class="mt-1 block w-full" x-model="editItem.telephone" required/>
                </div>
                <div>
                    <x-input-label value="Email"/>
                    <x-text-input name="email" type="email" class="mt-1 block w-full" x-model="editItem.email"/>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label value="Administrateur *"/>
                    <select name="user_id" required x-model="editItem.user_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="hidden" name="est_actif" value="0">
                    <input type="checkbox" id="est_actif" name="est_actif" value="1"
                           x-bind:checked="editItem?.est_actif"
                           class="rounded border-gray-300 text-blue-600 shadow-sm"/>
                    <label for="est_actif" class="text-sm text-gray-700">Cabinet actif</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-cabinet')">
                    Annuler
                </x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-cabinet" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer le cabinet</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible. Êtes-vous sûr de vouloir supprimer ce cabinet ?</p>
            <form method="POST" :action="`{{ url('admin/cabinets') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-cabinet')">
                        Annuler
                    </x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-admin-layout>
