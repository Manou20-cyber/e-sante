<x-guest-layout>

    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Créer mon compte patient</h1>
                <p class="text-sm text-gray-500">Accédez à votre espace santé personnalisé.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nom complet *"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name')" required autofocus autocomplete="name"/>
            <x-input-error :messages="$errors->get('name')" class="mt-1"/>
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail *"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email')" required autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1"/>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <x-input-label for="password" value="Mot de passe *"/>
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                              required autocomplete="new-password"/>
                <x-input-error :messages="$errors->get('password')" class="mt-1"/>
            </div>
            <div>
                <x-input-label for="password_confirmation" value="Confirmer *"/>
                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                              class="mt-1 block w-full" required autocomplete="new-password"/>
            </div>
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm mt-2">
            Créer mon compte
        </button>
    </form>

    <div class="mt-6 space-y-2 text-center text-sm text-gray-500">
        <p>
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Se connecter</a>
        </p>
        <p>
            Vous êtes un cabinet optique ?
            <a href="{{ route('register.cabinet') }}" class="text-teal-600 font-medium hover:underline">Inscrire mon cabinet</a>
        </p>
    </div>

</x-guest-layout>
