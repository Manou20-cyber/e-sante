<div class="space-y-4">
    <p class="text-sm text-gray-600 leading-relaxed">
        Une fois votre compte supprimé, toutes vos données seront définitivement effacées.
        Téléchargez vos informations avant de procéder.
    </p>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 font-semibold text-sm rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Supprimer mon compte
    </button>
</div>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900">Supprimer votre compte ?</h3>
                <p class="text-sm text-gray-500 mt-0.5">
                    Cette action est irréversible. Toutes vos données seront perdues définitivement.
                </p>
            </div>
        </div>

        <div class="mb-5">
            <x-input-label for="delete_password" value="Confirmez avec votre mot de passe *"/>
            <x-text-input id="delete_password" name="password" type="password"
                          class="mt-1 block w-full" placeholder="Votre mot de passe"/>
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1"/>
        </div>

        <div class="flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                Annuler
            </x-secondary-button>
            <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg transition">
                Supprimer définitivement
            </button>
        </div>
    </form>
</x-modal>
