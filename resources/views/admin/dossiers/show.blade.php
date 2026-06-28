<x-admin-layout title="Dossier — {{ $patient->user->name }}">

@php $isSuperAdmin = auth()->user()->hasRole('super_admin'); @endphp

<div x-data="{
    tab: 'dossier',
    editOrd: null,
    setEditOrd(ord) { this.editOrd = { ...ord }; $dispatch('open-modal', 'edit-ordonnance'); }
}">

    {{-- En-tête patient --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.dossiers.index') }}"
           class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl font-bold text-blue-700 shrink-0">
                {{ strtoupper(substr($patient->user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $patient->user->name }}</h2>
                <p class="text-sm text-gray-500">
                    {{ $patient->date_naissance?->format('d/m/Y') ?? 'Date de naissance non renseignée' }}
                    @if($patient->sexe) · {{ ucfirst($patient->sexe) }} @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Onglets --}}
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        @foreach([['dossier','Dossier médical'],['ordonnances','Ordonnances ('.($patient->dossierMedical?->ordonnances->count() ?? 0).')'],['consultations','Consultations ('.$patient->consultations->count().')'],['examens','Examens ('.$patient->examens->count().')']] as [$key,$label])
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2.5 text-sm font-medium transition -mb-px">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Onglet : Dossier médical --}}
    <div x-show="tab === 'dossier'">
        @if($patient->dossierMedical)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                @foreach([
                    ['Antécédents médicaux', $patient->dossierMedical->antecedents],
                    ['Allergies', $patient->dossierMedical->allergies],
                    ['Traitements en cours', $patient->dossierMedical->traitements_en_cours],
                    ['Notes personnelles', $patient->dossierMedical->notes],
                ] as [$label, $value])
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ $label }}</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $value ?: '—' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center">
                <p class="text-gray-500 text-sm">Le patient n'a pas encore renseigné son dossier médical.</p>
            </div>
        @endif
    </div>

    {{-- Onglet : Ordonnances --}}
    <div x-show="tab === 'ordonnances'">
        @unless($isSuperAdmin)
        <div class="flex justify-end mb-4">
            <button @click="$dispatch('open-modal', 'new-ordonnance')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter une ordonnance
            </button>
        </div>
        @endunless

        @if($patient->dossierMedical?->ordonnances->isEmpty() ?? true)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400 text-sm">
                Aucune ordonnance dans ce dossier.
            </div>
        @else
            <div class="space-y-3">
                @foreach($patient->dossierMedical->ordonnances as $ordonnance)
                    @php
                        $ordData = [
                            'id'               => $ordonnance->id,
                            'date'             => $ordonnance->date->format('Y-m-d'),
                            'sphere_od'        => $ordonnance->sphere_od,
                            'sphere_og'        => $ordonnance->sphere_og,
                            'cylindre_od'      => $ordonnance->cylindre_od,
                            'cylindre_og'      => $ordonnance->cylindre_og,
                            'axe_od'           => $ordonnance->axe_od,
                            'axe_og'           => $ordonnance->axe_og,
                            'addition_od'      => $ordonnance->addition_od,
                            'addition_og'      => $ordonnance->addition_og,
                            'ecart_pupillaire' => $ordonnance->ecart_pupillaire,
                            'notes'            => $ordonnance->notes,
                        ];
                    @endphp
                    <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex items-center">
                            <button @click="open = !open"
                                    class="flex-1 px-5 py-4 flex items-center justify-between hover:bg-gray-50 transition text-left rounded-l-xl min-w-0">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 text-sm">Ordonnance du {{ $ordonnance->date->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        OD {{ ($ordonnance->sphere_od ?? 0) > 0 ? '+' : '' }}{{ $ordonnance->sphere_od ?? '—' }}
                                        / OG {{ ($ordonnance->sphere_og ?? 0) > 0 ? '+' : '' }}{{ $ordonnance->sphere_og ?? '—' }}
                                    </p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform shrink-0 ml-3" :class="open ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            @unless($isSuperAdmin)
                                <button @click="setEditOrd({{ Js::from($ordData) }})"
                                        class="px-4 py-4 text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition rounded-r-xl shrink-0 border-l border-gray-100"
                                        title="Modifier l'ordonnance">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            @endunless
                        </div>
                        <div x-show="open" x-collapse class="px-5 pb-5">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                @foreach([
                                    ['OD Sphère', $ordonnance->sphere_od],
                                    ['OG Sphère', $ordonnance->sphere_og],
                                    ['OD Cylindre', $ordonnance->cylindre_od],
                                    ['OG Cylindre', $ordonnance->cylindre_og],
                                    ['OD Axe', $ordonnance->axe_od ? $ordonnance->axe_od.'°' : null],
                                    ['OG Axe', $ordonnance->axe_og ? $ordonnance->axe_og.'°' : null],
                                    ['Addition OD', $ordonnance->addition_od],
                                    ['Écart pupillaire', $ordonnance->ecart_pupillaire ? $ordonnance->ecart_pupillaire.' mm' : null],
                                ] as [$label, $val])
                                    @if($val !== null)
                                        <div class="bg-gray-50 rounded-lg p-2">
                                            <p class="text-gray-400">{{ $label }}</p>
                                            <p class="font-semibold text-gray-800">{{ is_numeric($val) && $val > 0 ? '+' : '' }}{{ $val }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @if($ordonnance->notes)
                                <p class="text-xs text-gray-500 mt-3">Note : {{ $ordonnance->notes }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Onglet : Consultations --}}
    <div x-show="tab === 'consultations'">
        @if($patient->consultations->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400 text-sm">
                Aucune consultation enregistrée.
            </div>
        @else
            <div class="space-y-3">
                @foreach($patient->consultations as $consultation)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $consultation->type }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $consultation->date->format('d/m/Y H:i') }}
                                    @if($consultation->cabinet) · {{ $consultation->cabinet->nom }} @endif
                                    @if($consultation->medecin) · {{ $consultation->medecin->name }} @endif
                                </p>
                            </div>
                            <div class="text-right">
                                @if($consultation->montant)
                                    <p class="font-semibold text-sm text-gray-900">{{ number_format($consultation->montant, 0, ',', ' ') }} XAF</p>
                                @endif
                                @if($consultation->examen)
                                    <span class="text-xs px-2 py-0.5 bg-teal-100 text-teal-700 rounded-full">Examen joint</span>
                                @endif
                            </div>
                        </div>
                        @if($consultation->diagnostic)
                            <p class="text-xs text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                                <span class="font-medium text-gray-500">Diagnostic : </span>{{ $consultation->diagnostic }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Onglet : Examens --}}
    <div x-show="tab === 'examens'">
        @if($patient->examens->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400 text-sm">
                Aucun examen optique enregistré.
            </div>
        @else
            <div class="space-y-3">
                @foreach($patient->examens as $examen)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="font-medium text-gray-900 text-sm">
                                Examen du {{ $examen->created_at->format('d/m/Y') }}
                                @if($examen->consultation) · {{ $examen->consultation->type }} @endif
                            </p>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                            @foreach([
                                ['Acuité OD (sans correction)', $examen->acuite_od, '/10'],
                                ['Acuité OG (sans correction)', $examen->acuite_og, '/10'],
                                ['Acuité OD (corrigée)', $examen->acuite_od_corrigee, '/10'],
                                ['Acuité OG (corrigée)', $examen->acuite_og_corrigee, '/10'],
                                ['Tension OD', $examen->tension_od, ' mmHg'],
                                ['Tension OG', $examen->tension_og, ' mmHg'],
                            ] as [$label, $val, $unit])
                                @if($val !== null)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-xs text-gray-400 mb-1">{{ $label }}</p>
                                        <p class="font-bold text-gray-800">{{ $val }}{{ $unit }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if($examen->observations)
                            <p class="text-xs text-gray-600 mt-3 bg-blue-50 rounded-lg px-3 py-2">
                                <span class="font-medium text-blue-700">Observations : </span>{{ $examen->observations }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @unless($isSuperAdmin)

    @php
        $champOrd = [
            ['sphere_od','Sphère OD'],['sphere_og','Sphère OG'],
            ['cylindre_od','Cylindre OD'],['cylindre_og','Cylindre OG'],
            ['axe_od','Axe OD (°)'],['axe_og','Axe OG (°)'],
            ['addition_od','Addition OD'],['addition_og','Addition OG'],
        ];
    @endphp

    {{-- Modal Nouvelle ordonnance --}}
    <x-modal name="new-ordonnance" max-width="lg" :show="$errors->isNotEmpty() && old('_modal') === 'new-ordonnance'">
        <form method="POST" action="{{ route('admin.dossiers.ordonnances.store', $patient) }}" class="p-6">
            @csrf
            <input type="hidden" name="_modal" value="new-ordonnance">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Nouvelle ordonnance</h2>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label for="ord_date" value="Date *"/>
                    <x-text-input id="ord_date" name="date" type="date" class="mt-1 block w-full"
                                  :value="old('date', now()->format('Y-m-d'))" required/>
                    <x-input-error :messages="$errors->get('date')" class="mt-1"/>
                </div>

                @foreach($champOrd as [$name, $label])
                    <div>
                        <x-input-label for="ord_{{ $name }}" value="{{ $label }}"/>
                        <x-text-input id="ord_{{ $name }}" name="{{ $name }}" type="number" step="0.25"
                                      class="mt-1 block w-full" :value="old($name)" placeholder="—"/>
                        <x-input-error :messages="$errors->get($name)" class="mt-1"/>
                    </div>
                @endforeach

                <div>
                    <x-input-label for="ord_ep" value="Écart pupillaire (mm)"/>
                    <x-text-input id="ord_ep" name="ecart_pupillaire" type="number" step="0.5"
                                  class="mt-1 block w-full" :value="old('ecart_pupillaire')" placeholder="Ex: 63"/>
                    <x-input-error :messages="$errors->get('ecart_pupillaire')" class="mt-1"/>
                </div>

                <div class="col-span-2">
                    <x-input-label for="ord_notes" value="Notes"/>
                    <textarea id="ord_notes" name="notes" rows="2"
                              class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'new-ordonnance')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer l'ordonnance</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier ordonnance --}}
    <x-modal name="edit-ordonnance" max-width="lg" :show="$errors->isNotEmpty() && old('_modal') === 'edit-ordonnance'">
        <form method="POST"
              :action="`{{ url('dashboard/dossiers/'.$patient->id.'/ordonnances') }}/${editOrd?.id}`"
              class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="_modal" value="edit-ordonnance">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Modifier l'ordonnance</h2>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label value="Date *"/>
                    <x-text-input name="date" type="date" class="mt-1 block w-full"
                                  x-model="editOrd.date" required/>
                    <x-input-error :messages="$errors->get('date')" class="mt-1"/>
                </div>

                @foreach($champOrd as [$name, $label])
                    <div>
                        <x-input-label value="{{ $label }}"/>
                        <x-text-input name="{{ $name }}" type="number" step="0.25"
                                      class="mt-1 block w-full" x-model="editOrd.{{ $name }}" placeholder="—"/>
                        <x-input-error :messages="$errors->get('{{ $name }}')" class="mt-1"/>
                    </div>
                @endforeach

                <div>
                    <x-input-label value="Écart pupillaire (mm)"/>
                    <x-text-input name="ecart_pupillaire" type="number" step="0.5"
                                  class="mt-1 block w-full" x-model="editOrd.ecart_pupillaire" placeholder="Ex: 63"/>
                    <x-input-error :messages="$errors->get('ecart_pupillaire')" class="mt-1"/>
                </div>

                <div class="col-span-2">
                    <x-input-label value="Notes"/>
                    <textarea name="notes" rows="2" x-model="editOrd.notes"
                              class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-ordonnance')">Annuler</x-secondary-button>
                <x-primary-button>Mettre à jour l'ordonnance</x-primary-button>
            </div>
        </form>
    </x-modal>

    @endunless

</div>
</x-admin-layout>
