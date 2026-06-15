<x-admin-layout title="Paramètres">

{{-- ═══════════════ SECTION MON CABINET (cabinet_admin uniquement) ═══════════════ --}}
@if(auth()->user()->hasRole('cabinet_admin') && $moncabinet)
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-1">Mon cabinet</h2>
    <p class="text-sm text-gray-500 mb-6">Personnalisez les informations et le logo de votre cabinet.</p>

    <form method="POST" action="{{ route('admin.parametres.cabinet') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Logo + identité --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Logo</h3>

                @if($moncabinet->logo)
                    <div class="flex items-center gap-3">
                        <img src="{{ Storage::url($moncabinet->logo) }}" alt="Logo"
                             class="w-16 h-16 rounded-xl object-contain border border-gray-200 bg-gray-50 p-1">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">{{ $moncabinet->nom }}</p>
                            <label class="flex items-center gap-1 mt-1 cursor-pointer">
                                <input type="checkbox" name="supprimer_logo" value="1"
                                       class="rounded border-gray-300 text-red-500">
                                <span class="text-xs text-red-500">Supprimer</span>
                            </label>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-500">Aucun logo défini</p>
                    </div>
                @endif

                <div>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, SVG — max 1 Mo</p>
                </div>
            </div>

            {{-- Infos cabinet --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Informations</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <x-input-label value="Nom du cabinet *"/>
                        <x-text-input name="nom" value="{{ $moncabinet->nom }}" class="mt-1 block w-full" required/>
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label value="Adresse *"/>
                        <x-text-input name="adresse" value="{{ $moncabinet->adresse }}" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label value="Ville *"/>
                        <x-text-input name="ville" value="{{ $moncabinet->ville }}" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label value="Code postal *"/>
                        <x-text-input name="code_postal" value="{{ $moncabinet->code_postal }}" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label value="Téléphone *"/>
                        <x-text-input name="telephone" type="tel" value="{{ $moncabinet->telephone }}" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label value="Email"/>
                        <x-text-input name="email" type="email" value="{{ $moncabinet->email }}" class="mt-1 block w-full"/>
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label value="Description"/>
                        <textarea name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ $moncabinet->description }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endif

{{-- ═══════════════ SECTION APPARENCE (super_admin uniquement) ═══════════════ --}}
@if(auth()->user()->hasRole('super_admin'))
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-1">Apparence</h2>
    <p class="text-sm text-gray-500 mb-6">Personnalisez le nom, le logo et les couleurs de la plateforme.</p>

    <form method="POST" action="{{ route('admin.parametres.apparence') }}" enctype="multipart/form-data"
          x-data="{ palette: '{{ $appSettings['palette_key'] }}' }">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Nom & Logo --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Identité</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la plateforme</label>
                    <input type="text" name="app_nom" value="{{ $appSettings['nom'] }}" required
                           class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    @if($appSettings['logo'])
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ Storage::url($appSettings['logo']) }}" alt="Logo actuel"
                                 class="w-14 h-14 object-contain rounded-lg border border-gray-200 bg-gray-50 p-1">
                            <div>
                                <p class="text-xs text-gray-500">Logo actuel</p>
                                <label class="flex items-center gap-1 mt-1 cursor-pointer">
                                    <input type="checkbox" name="supprimer_logo" value="1" class="rounded border-gray-300 text-red-500">
                                    <span class="text-xs text-red-500">Supprimer</span>
                                </label>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="app_logo" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, SVG — max 1 Mo</p>
                </div>
            </div>

            {{-- Sélecteur de palette --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Palette de couleurs</h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($palettes as $key => $palette)
                        <label class="cursor-pointer group" @click="palette = '{{ $key }}'">
                            <input type="radio" name="app_palette" value="{{ $key }}"
                                   {{ $appSettings['palette_key'] === $key ? 'checked' : '' }}
                                   x-model="palette" class="sr-only">

                            {{-- Carte palette --}}
                            <div class="relative rounded-xl overflow-hidden border-2 transition-all"
                                 :class="palette === '{{ $key }}' ? 'border-gray-900 shadow-md scale-105' : 'border-gray-200 hover:border-gray-400'">

                                {{-- Mini prévisualisation sidebar --}}
                                <div class="h-20 flex" style="background-color: {{ $palette['sidebar_bg'] }}">
                                    {{-- Sidebar mini --}}
                                    <div class="w-10 flex flex-col gap-1 p-1.5" style="background-color: {{ $palette['sidebar_bg'] }}">
                                        <div class="w-6 h-6 rounded-md mx-auto mb-1" style="background-color: {{ $palette['logo_bg'] }}"></div>
                                        <div class="h-1.5 rounded-full opacity-60" style="background-color: {{ $palette['sidebar_active'] }}"></div>
                                        <div class="h-1.5 rounded-full opacity-40" style="background-color: {{ $palette['sidebar_text'] }}"></div>
                                        <div class="h-1.5 rounded-full opacity-40" style="background-color: {{ $palette['sidebar_text'] }}"></div>
                                        <div class="h-1.5 rounded-full opacity-40" style="background-color: {{ $palette['sidebar_text'] }}"></div>
                                    </div>
                                    {{-- Contenu mini --}}
                                    <div class="flex-1 bg-gray-50 p-1.5 flex flex-col gap-1">
                                        <div class="h-2 w-3/4 rounded bg-gray-200"></div>
                                        <div class="h-1.5 w-1/2 rounded bg-gray-100"></div>
                                        <div class="mt-auto h-4 rounded-md w-full" style="background-color: {{ $palette['primary'] }}; opacity: 0.8"></div>
                                    </div>
                                </div>

                                {{-- Nom palette --}}
                                <div class="px-2 py-1.5 bg-white flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-700 truncate">{{ $palette['name'] }}</span>
                                    {{-- Checkmark si sélectionné --}}
                                    <div x-show="palette === '{{ $key }}'"
                                         class="w-4 h-4 rounded-full flex items-center justify-center shrink-0"
                                         style="background-color: {{ $palette['primary'] }}">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Bande de couleurs en bas --}}
                                <div class="h-1 flex">
                                    <div class="flex-1" style="background-color: {{ $palette['sidebar_bg'] }}"></div>
                                    <div class="flex-1" style="background-color: {{ $palette['sidebar_active'] }}"></div>
                                    <div class="flex-1" style="background-color: {{ $palette['logo_bg'] }}"></div>
                                    <div class="flex-1" style="background-color: {{ $palette['primary_light_bg'] }}"></div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Appliquer l'apparence
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ═══════════════ SECTION PARAMÈTRES SYSTÈME ═══════════════ --}}
<div x-data="{
    editItem: {},
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-parametre') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-parametre') }
}">

    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Paramètres système</h2>
            <p class="text-sm text-gray-500">{{ $parametres->total() }} paramètre(s) clé-valeur</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-parametre')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau paramètre
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Clé</th>
                        <th class="px-6 py-3 text-left font-medium">Valeur</th>
                        <th class="px-6 py-3 text-left font-medium">Groupe</th>
                        <th class="px-6 py-3 text-left font-medium">Description</th>
                        <th class="px-6 py-3 text-left font-medium">Public</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($parametres as $parametre)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-mono text-xs text-blue-700">{{ $parametre->cle }}</td>
                            <td class="px-6 py-3 text-gray-700 max-w-xs truncate">{{ $parametre->valeur ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $parametre->groupe }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs max-w-xs truncate">{{ $parametre->description ?? '-' }}</td>
                            <td class="px-6 py-3">
                                @if($parametre->est_public)
                                    <span class="text-green-600 text-xs font-medium">Oui</span>
                                @else
                                    <span class="text-gray-400 text-xs">Non</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="setEdit({{ $parametre }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="setDelete({{ $parametre->id }})"
                                            class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Aucun paramètre configuré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($parametres->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $parametres->links() }}</div>
        @endif
    </div>

    {{-- Modal Créer --}}
    <x-modal name="create-parametre" max-width="lg">
        <form method="POST" action="{{ route('admin.parametres.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouveau paramètre</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="pm_cle" value="Clé *"/>
                        <x-text-input id="pm_cle" name="cle" placeholder="ex: app.nom" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label for="pm_groupe" value="Groupe *"/>
                        <x-text-input id="pm_groupe" name="groupe" value="general" class="mt-1 block w-full" required/>
                    </div>
                </div>
                <div>
                    <x-input-label for="pm_valeur" value="Valeur"/>
                    <textarea id="pm_valeur" name="valeur" rows="2"
                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                </div>
                <div>
                    <x-input-label for="pm_desc" value="Description"/>
                    <x-text-input id="pm_desc" name="description" class="mt-1 block w-full"/>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="est_public" value="0">
                    <input type="checkbox" id="pm_public" name="est_public" value="1"
                           class="rounded border-gray-300 text-blue-600 shadow-sm"/>
                    <label for="pm_public" class="text-sm text-gray-700">Visible publiquement</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-parametre')">Annuler</x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-parametre" max-width="lg">
        <form method="POST" :action="`{{ url('dashboard/parametres') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier le paramètre</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Clé *"/>
                        <x-text-input name="cle" class="mt-1 block w-full" x-model="editItem.cle" required/>
                    </div>
                    <div>
                        <x-input-label value="Groupe *"/>
                        <x-text-input name="groupe" class="mt-1 block w-full" x-model="editItem.groupe" required/>
                    </div>
                </div>
                <div>
                    <x-input-label value="Valeur"/>
                    <textarea name="valeur" rows="2" x-model="editItem.valeur"
                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                </div>
                <div>
                    <x-input-label value="Description"/>
                    <x-text-input name="description" class="mt-1 block w-full" x-model="editItem.description"/>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="est_public" value="0">
                    <input type="checkbox" name="est_public" value="1"
                           x-bind:checked="editItem?.est_public"
                           class="rounded border-gray-300 text-blue-600 shadow-sm"/>
                    <span class="text-sm text-gray-700">Visible publiquement</span>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-parametre')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-parametre" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer le paramètre</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
            <form method="POST" :action="`{{ url('dashboard/parametres') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-parametre')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
@endif

</x-admin-layout>
