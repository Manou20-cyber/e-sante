<x-admin-layout title="Mes opticiens">

<div x-data="{
    editItem: {},
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-opticien') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-opticien') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Opticiens — {{ $cabinet->nom }}</h2>
            <p class="text-sm text-gray-500">{{ $opticiens->count() }} opticien(s) dans votre cabinet</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-opticien')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter un opticien
        </button>
    </div>

    @if($opticiens->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-gray-600 font-medium">Aucun opticien dans ce cabinet</p>
            <p class="text-sm text-gray-400 mt-1">Ajoutez vos opticiens pour qu'ils puissent configurer leurs créneaux.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($opticiens as $opticien)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-lg">
                                {{ strtoupper(substr($opticien->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $opticien->name }}</p>
                                <p class="text-xs text-gray-500">{{ $opticien->email }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">opticien</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $opticien->creneaux->where('est_actif', true)->count() }} créneau(x) actif(s)
                        @if($opticien->phone)
                            <span class="ml-2">· {{ $opticien->phone }}</span>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <button @click="setEdit({{ $opticien }})"
                                class="flex-1 text-xs py-1.5 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                            Modifier
                        </button>
                        <button @click="setDelete({{ $opticien->id }})"
                                class="text-xs py-1.5 px-3 border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition">
                            Supprimer
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal Créer --}}
    <x-modal name="create-opticien" max-width="md">
        <form method="POST" action="{{ route('admin.opticiens.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Nouvel opticien</h2>
            <div class="space-y-4">
                <div>
                    <x-input-label for="op_name" value="Nom complet *"/>
                    <x-text-input id="op_name" name="name" class="mt-1 block w-full" required/>
                    <x-input-error :messages="$errors->get('name')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="op_email" value="Email *"/>
                    <x-text-input id="op_email" name="email" type="email" class="mt-1 block w-full" required/>
                    <x-input-error :messages="$errors->get('email')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="op_phone" value="Téléphone"/>
                    <x-text-input id="op_phone" name="phone" type="tel" class="mt-1 block w-full"/>
                </div>
                <div>
                    <x-input-label for="op_pwd" value="Mot de passe temporaire *"/>
                    <x-text-input id="op_pwd" name="password" type="password" class="mt-1 block w-full" required/>
                    <x-input-error :messages="$errors->get('password')" class="mt-1"/>
                    <p class="text-xs text-gray-400 mt-1">L'opticien devra le changer à sa première connexion.</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-opticien')">Annuler</x-secondary-button>
                <x-primary-button>Créer l'opticien</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-opticien" max-width="md">
        <form method="POST" :action="`{{ url('dashboard/opticiens') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Modifier l'opticien</h2>
            <div class="space-y-4">
                <div>
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
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-opticien')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-opticien" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer l'opticien</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action supprimera le compte et tous les créneaux associés.</p>
            <form method="POST" :action="`{{ url('dashboard/opticiens') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-opticien')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-admin-layout>
