<form method="post" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    @method('put')

    <div>
        <x-input-label for="update_password_current_password" value="Mot de passe actuel *"/>
        <x-text-input id="update_password_current_password" name="current_password" type="password"
                      class="mt-1 block w-full" autocomplete="current-password"/>
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1"/>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="update_password_password" value="Nouveau mot de passe *"/>
            <x-text-input id="update_password_password" name="password" type="password"
                          class="mt-1 block w-full" autocomplete="new-password"/>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1"/>
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Confirmer *"/>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                          class="mt-1 block w-full" autocomplete="new-password"/>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1"/>
        </div>
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit"
                class="px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold text-sm rounded-xl transition shadow-sm">
            Mettre à jour le mot de passe
        </button>

        @if (session('status') === 'password-updated')
            <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                  class="inline-flex items-center gap-1 text-sm text-green-600 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Mot de passe mis à jour
            </span>
        @endif
    </div>
</form>
