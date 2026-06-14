<x-guest-layout>

    <div class="mb-8">
        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Nouveau mot de passe</h1>
        <p class="text-sm text-gray-500 mt-1">Choisissez un mot de passe sécurisé pour votre compte.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Adresse e-mail *"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $request->email)" required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1"/>
        </div>

        <div>
            <x-input-label for="password" value="Nouveau mot de passe *"/>
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                          required autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-1"/>
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe *"/>
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                          class="mt-1 block w-full" required autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1"/>
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm">
            Réinitialiser mon mot de passe
        </button>
    </form>

</x-guest-layout>
