<x-patient-layout title="Mon dossier médical">

<div x-data="{}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne gauche --}}
        <div class="space-y-4">

            {{-- Informations patient --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Informations patient</h3>
                </div>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nom</dt>
                        <dd class="font-medium text-gray-900">{{ auth()->user()->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Date de naissance</dt>
                        <dd class="font-medium text-gray-900">{{ $patient->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Sexe</dt>
                        <dd class="font-medium text-gray-900">{{ $patient->sexe ? ucfirst($patient->sexe) : '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Téléphone</dt>
                        <dd class="font-medium text-gray-900">{{ auth()->user()->phone ?? '—' }}</dd>
                    </div>
                </dl>
                <a href="{{ route('profile.edit') }}"
                   class="mt-4 block text-center text-xs text-blue-600 hover:underline">
                    Modifier mon profil
                </a>
            </div>

            {{-- Informations médicales --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900">Informations médicales</h3>
                    <button @click="$dispatch('open-modal', 'edit-dossier')"
                            class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 font-medium px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </button>
                </div>

                @if($dossier)
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Antécédents médicaux</p>
                            <p class="text-gray-700 leading-relaxed">{{ $dossier->antecedents ?: '—' }}</p>
                        </div>
                        <div class="border-t border-gray-50 pt-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Allergies</p>
                            <p class="text-gray-700 leading-relaxed">{{ $dossier->allergies ?: '—' }}</p>
                        </div>
                        <div class="border-t border-gray-50 pt-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Traitements en cours</p>
                            <p class="text-gray-700 leading-relaxed">{{ $dossier->traitements_en_cours ?: '—' }}</p>
                        </div>
                        @if($dossier->notes)
                            <div class="border-t border-gray-50 pt-3">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Notes personnelles</p>
                                <p class="text-gray-700 leading-relaxed">{{ $dossier->notes }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-400 mb-3">Votre dossier médical n'a pas encore été renseigné.</p>
                        <button @click="$dispatch('open-modal', 'edit-dossier')"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                            Renseigner mon dossier
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Colonne droite : ordonnances + consultations --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Ordonnances --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Ordonnances</h3>
                    <span class="text-xs text-gray-400">{{ $dossier?->ordonnances->count() ?? 0 }} ordonnance(s)</span>
                </div>
                @forelse($dossier?->ordonnances ?? [] as $ordonnance)
                    <div x-data="{ open: false }" class="border-b border-gray-50 last:border-0">
                        <button @click="open = !open"
                                class="w-full px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition text-left">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Ordonnance du {{ $ordonnance->date->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-400">
                                    OD: {{ $ordonnance->sphere_od > 0 ? '+' : '' }}{{ $ordonnance->sphere_od ?? '—' }}
                                    | OG: {{ $ordonnance->sphere_og > 0 ? '+' : '' }}{{ $ordonnance->sphere_og ?? '—' }}
                                </p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="px-5 pb-4">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                @foreach([
                                    ['OD Sphère', $ordonnance->sphere_od],
                                    ['OG Sphère', $ordonnance->sphere_og],
                                    ['OD Cylindre', $ordonnance->cylindre_od],
                                    ['OG Cylindre', $ordonnance->cylindre_og],
                                    ['OD Axe', $ordonnance->axe_od],
                                    ['OG Axe', $ordonnance->axe_og],
                                    ['Addition OD', $ordonnance->addition_od],
                                    ['Écart pupillaire', $ordonnance->ecart_pupillaire ? $ordonnance->ecart_pupillaire.'mm' : null],
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
                                <p class="text-xs text-gray-500 mt-2">Note : {{ $ordonnance->notes }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">
                        Aucune ordonnance dans votre dossier.
                    </div>
                @endforelse
            </div>

            {{-- Dernières consultations --}}
            @if($consultations->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Dernières consultations</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($consultations as $consultation)
                            <div class="px-5 py-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $consultation->type }}</p>
                                        <p class="text-xs text-gray-500">{{ $consultation->date->format('d/m/Y') }} — {{ $consultation->cabinet->nom }}</p>
                                    </div>
                                    @if($consultation->diagnostic)
                                        <p class="text-xs text-gray-400 max-w-xs truncate ml-4">{{ $consultation->diagnostic }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Modal édition dossier médical --}}
    <x-modal name="edit-dossier" max-width="lg" :show="$errors->isNotEmpty()">
        <form method="POST" action="{{ route('patient.dossier.update') }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Dossier médical</h2>
                </div>
                <button type="button" @click="$dispatch('close-modal', 'edit-dossier')"
                        class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <p class="text-xs text-gray-500 bg-gray-50 rounded-lg px-3 py-2 mb-5">
                Ces informations sont strictement confidentielles et accessibles uniquement à votre équipe médicale.
            </p>

            <div class="space-y-4">
                <div>
                    <x-input-label for="antecedents" value="Antécédents médicaux"/>
                    <textarea id="antecedents" name="antecedents" rows="3" maxlength="2000"
                              class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                              placeholder="Maladies chroniques, chirurgies, hospitalisations...">{{ old('antecedents', $dossier?->antecedents) }}</textarea>
                    <x-input-error :messages="$errors->get('antecedents')" class="mt-1"/>
                </div>

                <div>
                    <x-input-label for="allergies" value="Allergies connues"/>
                    <textarea id="allergies" name="allergies" rows="2" maxlength="1000"
                              class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                              placeholder="Médicaments, aliments, matériaux...">{{ old('allergies', $dossier?->allergies) }}</textarea>
                    <x-input-error :messages="$errors->get('allergies')" class="mt-1"/>
                </div>

                <div>
                    <x-input-label for="traitements_en_cours" value="Traitements en cours"/>
                    <textarea id="traitements_en_cours" name="traitements_en_cours" rows="2" maxlength="1000"
                              class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                              placeholder="Médicaments, posologie...">{{ old('traitements_en_cours', $dossier?->traitements_en_cours) }}</textarea>
                    <x-input-error :messages="$errors->get('traitements_en_cours')" class="mt-1"/>
                </div>

                <div>
                    <x-input-label for="notes" value="Notes personnelles"/>
                    <textarea id="notes" name="notes" rows="2" maxlength="2000"
                              class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                              placeholder="Informations complémentaires à partager avec votre opticien...">{{ old('notes', $dossier?->notes) }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-1"/>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-dossier')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

</div>
</x-patient-layout>
