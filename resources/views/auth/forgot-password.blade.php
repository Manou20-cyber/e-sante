<x-guest-layout>

    <div class="mb-8">
        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Mot de passe oublié ?</h1>
        <p class="text-sm text-gray-500 mt-1">
            Saisissez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail *"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email')" required autofocus/>
            <x-input-error :messages="$errors->get('email')" class="mt-1"/>
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm">
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à la connexion
        </a>
    </div>

</x-guest-layout>
