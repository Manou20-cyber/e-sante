<x-admin-layout title="Mon planning">

@php
    $joursMap = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
@endphp

<div x-data="{
    editItem: { id: null, jour_semaine: '1', heure_debut: '08:00', heure_fin: '17:00', duree_consultation: '30', prix: 0, est_actif: true },
    deleteId: null,
    setEdit(item) {
        this.editItem = {
            ...item,
            heure_debut:        (item.heure_debut ?? '').substring(0, 5),
            heure_fin:          (item.heure_fin   ?? '').substring(0, 5),
            jour_semaine:       String(item.jour_semaine),
            duree_consultation: String(item.duree_consultation),
        };
        $dispatch('open-modal', 'edit-creneau');
    },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-creneau') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Mon planning</h2>
            <p class="text-sm text-gray-500">
                {{ auth()->user()->name }}
                @if($cabinet) — {{ $cabinet->nom }} @endif
            </p>
        </div>
        <button @click="$dispatch('open-modal', 'create-creneau')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter un créneau
        </button>
    </div>

    @if($creneaux->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-600 font-medium">Aucun créneau configuré</p>
            <p class="text-sm text-gray-400 mt-1">Définissez vos disponibilités pour que les patients puissent prendre rendez-vous.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($creneaux as $creneau)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 {{ !$creneau->est_actif ? 'opacity-60' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-semibold text-gray-900 text-sm">{{ $joursMap[$creneau->jour_semaine] ?? '?' }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $creneau->est_actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $creneau->est_actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-blue-700 mb-1">
                        {{ substr($creneau->heure_debut, 0, 5) }} – {{ substr($creneau->heure_fin, 0, 5) }}
                    </p>
                    <div class="text-xs text-gray-500 space-y-0.5 mb-3">
                        <p>Durée par consultation : {{ $creneau->duree_consultation }} min</p>
                        <p class="font-semibold text-gray-700">
                            Prix : {{ $creneau->prix ? number_format($creneau->prix, 0, ',', ' ').' XAF' : 'Non défini' }}
                        </p>
                        @if($creneau->accepte_video)
                            <p class="inline-flex items-center gap-1 text-purple-700 font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 10l4.553-2.069A1 1 0 0121 8.868v6.264a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Vidéo acceptée
                            </p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button @click="setEdit({{ $creneau }})"
                                class="flex-1 text-xs py-1 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                            Modifier
                        </button>
                        <button @click="setDelete({{ $creneau->id }})"
                                class="text-xs py-1 px-2 border border-red-200 text-red-400 rounded-lg hover:bg-red-50 transition">
                            ×
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @php $selectClass = 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm'; @endphp

    {{-- Modal Créer — rouvre automatiquement si erreurs de validation --}}
    <x-modal name="create-creneau" max-width="md" :show="$errors->isNotEmpty() && old('_modal') === 'create-creneau'">
        <form method="POST" action="{{ route('admin.planning.store') }}" class="p-6" novalidate>
            @csrf
            <input type="hidden" name="_modal" value="create-creneau">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Nouveau créneau</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label value="Jours de la semaine *"/>
                    <p class="text-xs text-gray-400 mb-2">Sélectionnez un ou plusieurs jours.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([1=>'Lun',2=>'Mar',3=>'Mer',4=>'Jeu',5=>'Ven',6=>'Sam',7=>'Dim'] as $n => $j)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="jours_semaine[]" value="{{ $n }}"
                                       class="sr-only peer"/>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium border border-gray-200 text-gray-600
                                             peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600
                                             hover:border-blue-400 hover:text-blue-600 transition select-none">
                                    {{ $j }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('jours_semaine')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="cr_debut" value="Heure début *"/>
                    <x-text-input id="cr_debut" name="heure_debut" type="time" class="mt-1 block w-full" :value="old('heure_debut', '08:00')"/>
                    <x-input-error :messages="$errors->get('heure_debut')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="cr_fin" value="Heure fin *"/>
                    <x-text-input id="cr_fin" name="heure_fin" type="time" class="mt-1 block w-full" :value="old('heure_fin', '17:00')"/>
                    <x-input-error :messages="$errors->get('heure_fin')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="cr_duree" value="Durée par RDV (min) *"/>
                    <select id="cr_duree" name="duree_consultation" class="{{ $selectClass }}">
                        @foreach([15=>'15 min',20=>'20 min',30=>'30 min',45=>'45 min',60=>'1 heure',90=>'1h30'] as $val => $label)
                            <option value="{{ $val }}" {{ old('duree_consultation', 30) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('duree_consultation')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="cr_prix" value="Prix consultation (XAF) *"/>
                    <x-text-input id="cr_prix" name="prix" type="number" min="0" step="500"
                                  class="mt-1 block w-full" placeholder="Ex: 15000"
                                  :value="old('prix')"/>
                    <x-input-error :messages="$errors->get('prix')" class="mt-1"/>
                </div>
                <div class="col-span-2">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                        <input type="hidden" name="accepte_video" value="0">
                        <input type="checkbox" name="accepte_video" value="1"
                               {{ old('accepte_video') ? 'checked' : '' }}
                               class="mt-0.5 rounded border-gray-300 text-purple-600 focus:ring-purple-500"/>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Accepter les consultations vidéo</p>
                            <p class="text-xs text-gray-400 mt-0.5">Les patients pourront demander une téléconsultation pour ce créneau.</p>
                        </div>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-creneau')">Annuler</x-secondary-button>
                <x-primary-button>Ajouter le créneau</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-creneau" max-width="md" :show="$errors->isNotEmpty() && old('_modal') === 'edit-creneau'">
        <form method="POST" :action="`{{ url('dashboard/planning') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="_modal" value="edit-creneau">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Modifier le créneau</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label value="Jour *"/>
                    <select name="jour_semaine" required x-model="editItem.jour_semaine" class="{{ $selectClass }}">
                        @foreach([1=>'Lundi',2=>'Mardi',3=>'Mercredi',4=>'Jeudi',5=>'Vendredi',6=>'Samedi',7=>'Dimanche'] as $n => $j)
                            <option value="{{ $n }}">{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Heure début *"/>
                    <x-text-input name="heure_debut" type="time" class="mt-1 block w-full" x-model="editItem.heure_debut" required/>
                    <x-input-error :messages="$errors->get('heure_debut')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label value="Heure fin *"/>
                    <x-text-input name="heure_fin" type="time" class="mt-1 block w-full" x-model="editItem.heure_fin" required/>
                    <x-input-error :messages="$errors->get('heure_fin')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label value="Durée (min) *"/>
                    <select name="duree_consultation" required x-model="editItem.duree_consultation" class="{{ $selectClass }}">
                        <option value="15">15 min</option>
                        <option value="20">20 min</option>
                        <option value="30">30 min</option>
                        <option value="45">45 min</option>
                        <option value="60">1 heure</option>
                        <option value="90">1h30</option>
                    </select>
                </div>
                <div>
                    <x-input-label value="Prix (XAF) *"/>
                    <x-text-input name="prix" type="number" min="0" step="500" class="mt-1 block w-full" x-model="editItem.prix" required/>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    <input type="hidden" name="est_actif" value="0">
                    <input type="checkbox" name="est_actif" value="1" x-bind:checked="editItem?.est_actif"
                           class="rounded border-gray-300 text-blue-600"/>
                    <span class="text-sm text-gray-700">Créneau actif</span>
                </div>
                <div class="col-span-2">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                        <input type="hidden" name="accepte_video" value="0">
                        <input type="checkbox" name="accepte_video" value="1"
                               x-bind:checked="editItem?.accepte_video"
                               class="mt-0.5 rounded border-gray-300 text-purple-600 focus:ring-purple-500"/>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Accepter les consultations vidéo</p>
                            <p class="text-xs text-gray-400 mt-0.5">Les patients pourront demander une téléconsultation pour ce créneau.</p>
                        </div>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-creneau')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-creneau" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer ce créneau</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
            <form method="POST" :action="`{{ url('dashboard/planning') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-creneau')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-admin-layout>
