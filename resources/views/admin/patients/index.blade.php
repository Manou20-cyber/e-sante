<x-admin-layout title="Patients">

<div x-data="{
    editItem: {},
    deleteId: null,
    setEdit(item) { this.editItem = {...item, ...item.user}; $dispatch('open-modal', 'edit-patient') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-patient') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Patients</h2>
            <p class="text-sm text-gray-500">{{ $patients->total() }} patient(s)</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-patient')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau patient
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Nom</th>
                        <th class="px-6 py-3 text-left font-medium">Email</th>
                        <th class="px-6 py-3 text-left font-medium">Téléphone</th>
                        <th class="px-6 py-3 text-left font-medium">Date de naissance</th>
                        <th class="px-6 py-3 text-left font-medium">Ville</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $patient->user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $patient->user->email }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $patient->user->phone ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $patient->date_naissance?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $patient->ville ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="setEdit({{ $patient->load('user') }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="setDelete({{ $patient->id }})"
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
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Aucun patient enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $patients->links() }}</div>
        @endif
    </div>

    {{-- Modal Créer --}}
    <x-modal name="create-patient" max-width="lg">
        <form method="POST" action="{{ route('admin.patients.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouveau patient</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label for="p_name" value="Nom complet *"/>
                    <x-text-input id="p_name" name="name" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="p_email" value="Email *"/>
                    <x-text-input id="p_email" name="email" type="email" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="p_phone" value="Téléphone"/>
                    <x-text-input id="p_phone" name="phone" type="tel" class="mt-1 block w-full"/>
                </div>
                <div>
                    <x-input-label for="p_password" value="Mot de passe *"/>
                    <x-text-input id="p_password" name="password" type="password" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="p_dn" value="Date de naissance"/>
                    <x-text-input id="p_dn" name="date_naissance" type="date" class="mt-1 block w-full"/>
                </div>
                <div>
                    <x-input-label for="p_sexe" value="Sexe"/>
                    <select id="p_sexe" name="sexe"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sélectionner --</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="p_adresse" value="Adresse"/>
                    <x-text-input id="p_adresse" name="adresse" class="mt-1 block w-full"/>
                </div>
                <div>
                    <x-input-label for="p_ville" value="Ville"/>
                    <x-text-input id="p_ville" name="ville" class="mt-1 block w-full"/>
                </div>
                <div>
                    <x-input-label for="p_cp" value="Code postal"/>
                    <x-text-input id="p_cp" name="code_postal" class="mt-1 block w-full"/>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-patient')">Annuler</x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-patient" max-width="lg">
        <form method="POST" :action="`{{ url('dashboard/patients') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier le patient</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label value="Nom complet *"/>
                    <x-text-input name="name" class="mt-1 block w-full" x-model="editItem.name" required/>
                </div>
                <div>
                    <x-input-label value="Email *"/>
                    <x-text-input name="email" type="email" class="mt-1 block w-full" x-model="editItem.email" required/>
                </div>
                <div>
                    <x-input-label value="Téléphone"/>
                    <x-text-input name="phone" type="tel" class="mt-1 block w-full" x-model="editItem.phone"/>
                </div>
                <div>
                    <x-input-label value="Date de naissance"/>
                    <x-text-input name="date_naissance" type="date" class="mt-1 block w-full" x-model="editItem.date_naissance"/>
                </div>
                <div>
                    <x-input-label value="Sexe"/>
                    <select name="sexe" x-model="editItem.sexe"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sélectionner --</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label value="Adresse"/>
                    <x-text-input name="adresse" class="mt-1 block w-full" x-model="editItem.adresse"/>
                </div>
                <div>
                    <x-input-label value="Ville"/>
                    <x-text-input name="ville" class="mt-1 block w-full" x-model="editItem.ville"/>
                </div>
                <div>
                    <x-input-label value="Code postal"/>
                    <x-text-input name="code_postal" class="mt-1 block w-full" x-model="editItem.code_postal"/>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-patient')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-patient" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer le patient</h2>
            <p class="text-sm text-gray-500 mb-6">Le compte utilisateur associé sera également supprimé. Cette action est irréversible.</p>
            <form method="POST" :action="`{{ url('dashboard/patients') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-patient')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-admin-layout>
