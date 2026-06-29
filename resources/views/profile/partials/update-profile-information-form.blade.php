<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-4" enctype="multipart/form-data">
    @csrf
    @method('patch')

    @php $patient = $user->patient; @endphp

    {{-- Photo de profil --}}
    <div class="flex items-center gap-5 pb-4 border-b border-gray-100">
        <div class="shrink-0">
            @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
            @else
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="flex-1">
            <x-input-label for="avatar" value="Photo de profil"/>
            <input id="avatar" name="avatar" type="file" accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"/>
            <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF — max 2 Mo</p>
            <x-input-error class="mt-1" :messages="$errors->get('avatar')"/>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <x-input-label for="name" value="Nom complet *"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required autofocus autocomplete="name"/>
            <x-input-error class="mt-1" :messages="$errors->get('name')"/>
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail *"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username"/>
            <x-input-error class="mt-1" :messages="$errors->get('email')"/>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-full px-2 py-0.5">
                        Non vérifié
                    </span>
                    <button form="send-verification" class="text-xs text-blue-600 hover:underline">
                        Renvoyer la vérification
                    </button>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-1 text-xs text-green-600 font-medium">Lien envoyé !</p>
                @endif
            @endif
        </div>

        <div>
            <x-input-label for="phone" value="Téléphone"/>
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                          :value="old('phone', $user->phone)" autocomplete="tel"/>
            <x-input-error class="mt-1" :messages="$errors->get('phone')"/>
        </div>
    </div>

    @if($patient)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Informations médicales</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="date_naissance" value="Date de naissance"/>
                    <x-text-input id="date_naissance" name="date_naissance" type="date" class="mt-1 block w-full"
                                  :value="old('date_naissance', $patient->date_naissance?->format('Y-m-d'))"/>
                    <x-input-error class="mt-1" :messages="$errors->get('date_naissance')"/>
                </div>

                <div>
                    <x-input-label for="sexe" value="Sexe"/>
                    <select id="sexe" name="sexe"
                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm">
                        <option value="">— Sélectionner —</option>
                        <option value="M" @selected(old('sexe', $patient->sexe) === 'M')>Masculin</option>
                        <option value="F" @selected(old('sexe', $patient->sexe) === 'F')>Féminin</option>
                        <option value="autre" @selected(old('sexe', $patient->sexe) === 'autre')>Autre</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="adresse" value="Adresse"/>
                    <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full"
                                  :value="old('adresse', $patient->adresse)"/>
                </div>

                <div>
                    <x-input-label for="ville" value="Ville"/>
                    <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full"
                                  :value="old('ville', $patient->ville)"/>
                </div>

                <div>
                    <x-input-label for="code_postal" value="Code postal"/>
                    <x-text-input id="code_postal" name="code_postal" type="text" class="mt-1 block w-full"
                                  :value="old('code_postal', $patient->code_postal)"/>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center gap-4 pt-2">
        <button type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition shadow-sm">
            Enregistrer les modifications
        </button>

        @if (session('status') === 'profile-updated')
            <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                  class="inline-flex items-center gap-1 text-sm text-green-600 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Profil mis à jour
            </span>
        @endif
    </div>
</form>
