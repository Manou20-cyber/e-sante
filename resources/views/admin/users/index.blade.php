<x-admin-layout title="Utilisateurs">

<div x-data="{
    editItem: {},
    deleteId: null,
    setEdit(item) { this.editItem = {...item, role: item.roles?.[0]?.name ?? ''}; $dispatch('open-modal', 'edit-user') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-user') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Utilisateurs</h2>
            <p class="text-sm text-gray-500">{{ $users->total() }} utilisateur(s)</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-user')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel utilisateur
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
                        <th class="px-6 py-3 text-left font-medium">Rôle</th>
                        <th class="px-6 py-3 text-left font-medium">Inscrit le</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-3">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="setEdit({{ $user->load('roles') }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button @click="setDelete({{ $user->id }})"
                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Aucun utilisateur</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Modal Créer --}}
    <x-modal name="create-user" max-width="lg">
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouvel utilisateur</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input-label for="u_name" value="Nom complet *"/>
                    <x-text-input id="u_name" name="name" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="u_email" value="Email *"/>
                    <x-text-input id="u_email" name="email" type="email" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="u_phone" value="Téléphone"/>
                    <x-text-input id="u_phone" name="phone" type="tel" class="mt-1 block w-full"/>
                </div>
                <div>
                    <x-input-label for="u_password" value="Mot de passe *"/>
                    <x-text-input id="u_password" name="password" type="password" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="u_role" value="Rôle *"/>
                    <select id="u_role" name="role" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sélectionner --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-user')">Annuler</x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-user" max-width="lg">
        <form method="POST" :action="`{{ url('dashboard/users') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier l'utilisateur</h2>
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
                    <x-input-label value="Nouveau mot de passe"/>
                    <x-text-input name="password" type="password" class="mt-1 block w-full" placeholder="Laisser vide pour ne pas changer"/>
                </div>
                <div>
                    <x-input-label value="Rôle *"/>
                    <select name="role" required x-model="editItem.role"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-user')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-user" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer l'utilisateur</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible. Êtes-vous sûr ?</p>
            <form method="POST" :action="`{{ url('dashboard/users') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-user')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-admin-layout>
