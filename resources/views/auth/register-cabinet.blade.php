<x-guest-layout>
    <div class="mb-6">
        <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour
        </a>
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 bg-teal-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 text-lg">Inscription cabinet optique</h2>
                <p class="text-xs text-gray-500">Votre compte sera activé après validation par notre équipe.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register.cabinet') }}" class="space-y-5">
        @csrf

        {{-- Informations du responsable --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 pb-2 border-b border-gray-100">
                Responsable du cabinet
            </h3>
            <div class="space-y-3">
                <div>
                    <x-input-label for="name" value="Nom complet *"/>
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                  :value="old('name')" required autofocus autocomplete="name"/>
                    <x-input-error :messages="$errors->get('name')" class="mt-1"/>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="email" value="Email *"/>
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                      :value="old('email')" required autocomplete="email"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-1"/>
                    </div>
                    <div>
                        <x-input-label for="phone" value="Téléphone"/>
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                                      :value="old('phone')" autocomplete="tel"/>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1"/>
                    </div>
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
            </div>
        </div>

        {{-- Informations du cabinet --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 pb-2 border-b border-gray-100">
                Informations du cabinet
            </h3>
            <div class="space-y-3">
                <div>
                    <x-input-label for="cabinet_nom" value="Nom du cabinet *"/>
                    <x-text-input id="cabinet_nom" name="cabinet_nom" type="text" class="mt-1 block w-full"
                                  :value="old('cabinet_nom')" required/>
                    <x-input-error :messages="$errors->get('cabinet_nom')" class="mt-1"/>
                </div>

                <div>
                    <x-input-label for="cabinet_adresse" value="Adresse *"/>
                    <x-text-input id="cabinet_adresse" name="cabinet_adresse" type="text" class="mt-1 block w-full"
                                  :value="old('cabinet_adresse')" required/>
                    <x-input-error :messages="$errors->get('cabinet_adresse')" class="mt-1"/>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="cabinet_ville" value="Ville *"/>
                        <x-text-input id="cabinet_ville" name="cabinet_ville" type="text" class="mt-1 block w-full"
                                      :value="old('cabinet_ville')" required/>
                        <x-input-error :messages="$errors->get('cabinet_ville')" class="mt-1"/>
                    </div>
                    <div>
                        <x-input-label for="cabinet_code_postal" value="Code postal *"/>
                        <x-text-input id="cabinet_code_postal" name="cabinet_code_postal" type="text"
                                      class="mt-1 block w-full" :value="old('cabinet_code_postal')" required/>
                        <x-input-error :messages="$errors->get('cabinet_code_postal')" class="mt-1"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="cabinet_telephone" value="Téléphone du cabinet *"/>
                        <x-text-input id="cabinet_telephone" name="cabinet_telephone" type="tel"
                                      class="mt-1 block w-full" :value="old('cabinet_telephone')" required/>
                        <x-input-error :messages="$errors->get('cabinet_telephone')" class="mt-1"/>
                    </div>
                    <div>
                        <x-input-label for="cabinet_email" value="Email du cabinet"/>
                        <x-text-input id="cabinet_email" name="cabinet_email" type="email"
                                      class="mt-1 block w-full" :value="old('cabinet_email')"/>
                        <x-input-error :messages="$errors->get('cabinet_email')" class="mt-1"/>
                    </div>
                </div>

                <div>
                    <x-input-label for="cabinet_siret" value="Numéro SIRET"/>
                    <x-text-input id="cabinet_siret" name="cabinet_siret" type="text" class="mt-1 block w-full"
                                  :value="old('cabinet_siret')" placeholder="Ex: 123 456 789 00012"/>
                    <x-input-error :messages="$errors->get('cabinet_siret')" class="mt-1"/>
                </div>
            </div>
        </div>

        <x-primary-button class="w-full justify-center py-3 bg-teal-600 hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900">
            Soumettre ma demande d'inscription
        </x-primary-button>

        <p class="text-center text-xs text-gray-400">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-teal-600 hover:underline">Se connecter</a>
        </p>
    </form>
</x-guest-layout>
