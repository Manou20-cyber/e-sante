<x-guest-layout>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Connexion</h1>
        <p class="text-sm text-gray-500 mt-1">Accédez à votre espace de suivi médical optique.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email')" required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1"/>
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Mot de passe"/>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                          required autocomplete="current-password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-1"/>
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"/>
            <label for="remember_me" class="text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm">
            Se connecter
        </button>
    </form>

    <div class="mt-6 space-y-2 text-center text-sm text-gray-500">
        <p>
            Pas encore de compte patient ?
            <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">S'inscrire</a>
        </p>
        <p>
            Vous êtes un cabinet optique ?
            <a href="{{ route('register.cabinet') }}" class="text-teal-600 font-medium hover:underline">Inscrire mon cabinet</a>
        </p>
    </div>

</x-guest-layout>
