<x-patient-layout title="Mes rendez-vous">

@php
    $types = [
        'bilan_visuel'        => 'Bilan visuel',
        'consultation'        => 'Consultation',
        'controle'            => 'Contrôle',
        'adaptation_lentilles'=> 'Adaptation lentilles',
        'autre'               => 'Autre',
    ];
    $colors = [
        'en_attente' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'confirme'   => 'bg-blue-100 text-blue-700 border-blue-200',
        'termine'    => 'bg-green-100 text-green-700 border-green-200',
        'annule'     => 'bg-red-100 text-red-700 border-red-200',
        'absent'     => 'bg-gray-100 text-gray-600 border-gray-200',
    ];
    $labels = [
        'en_attente' => 'En attente',
        'confirme'   => 'Confirmé',
        'termine'    => 'Terminé',
        'annule'     => 'Annulé',
        'absent'     => 'Absent',
    ];
@endphp

<div x-data="{
    showRdv: null,
    editRdv: null,
    deleteId: null,
    setShow(rdv)   { this.showRdv = rdv;  $dispatch('open-modal', 'show-rdv') },
    setEdit(rdv)   { this.editRdv = rdv;  $dispatch('open-modal', 'edit-rdv') },
    setDelete(id)  { this.deleteId = id;  $dispatch('open-modal', 'cancel-rdv') },
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Mes rendez-vous</h2>
            <p class="text-sm text-gray-500">{{ $rendezvous->total() }} rendez-vous au total</p>
        </div>
        <a href="{{ route('patient.cabinets.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Prendre un rendez-vous
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if($rendezvous->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-gray-600 font-medium">Aucun rendez-vous pour le moment</p>
            <p class="text-sm text-gray-400 mt-1">Prenez votre premier rendez-vous en cliquant sur le bouton ci-dessus.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($rendezvous as $rdv)
                @php
                    $isPast      = $rdv->date->isPast();
                    $canModify   = !$isPast && $rdv->statut === 'en_attente';
                    $canCancel   = !$isPast && !in_array($rdv->statut, ['termine', 'annule']);
                    $rdvJson     = json_encode([
                        'id'          => $rdv->id,
                        'cabinet'     => $rdv->cabinet->nom,
                        'opticien'    => $rdv->opticien?->name,
                        'date'        => $rdv->date->format('Y-m-d\TH:i'),
                        'date_label'  => $rdv->date->translatedFormat('l d F Y à H:i'),
                        'type'        => $rdv->type,
                        'type_label'  => $types[$rdv->type] ?? $rdv->type,
                        'duree'       => $rdv->duree,
                        'statut'      => $rdv->statut,
                        'statut_label'=> $labels[$rdv->statut] ?? $rdv->statut,
                        'motif'       => $rdv->motif,
                        'notes'       => $rdv->notes,
                    ]);
                @endphp

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 {{ $isPast ? 'opacity-70' : '' }}">

                    {{-- Bloc date --}}
                    <div class="w-12 h-12 rounded-xl {{ $isPast ? 'bg-gray-100' : 'bg-blue-50' }} flex flex-col items-center justify-center shrink-0">
                        <span class="text-sm font-bold {{ $isPast ? 'text-gray-500' : 'text-blue-700' }} leading-none">{{ $rdv->date->format('d') }}</span>
                        <span class="text-xs {{ $isPast ? 'text-gray-400' : 'text-blue-400' }} uppercase">{{ $rdv->date->format('M') }}</span>
                    </div>

                    {{-- Infos --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm">{{ $rdv->cabinet->nom }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $rdv->date->format('H:i') }}
                            @if($rdv->opticien) · {{ $rdv->opticien->name }} @endif
                            · {{ $types[$rdv->type] ?? $rdv->type }} ({{ $rdv->duree }} min)
                        </p>
                        @if($rdv->motif)
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $rdv->motif }}</p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 shrink-0">
                        {{-- Statut --}}
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium border {{ $colors[$rdv->statut] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                            {{ $labels[$rdv->statut] ?? $rdv->statut }}
                        </span>

                        {{-- Voir --}}
                        <button @click="setShow({{ $rdvJson }})"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Voir les détails">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>

                        {{-- Modifier --}}
                        @if($canModify)
                            <button @click="setEdit({{ $rdvJson }})"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-teal-600 hover:bg-teal-50 transition" title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        @endif

                        {{-- Annuler --}}
                        @if($canCancel)
                            <button @click="setDelete({{ $rdv->id }})"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Annuler">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($rendezvous->hasPages())
            <div class="mt-4">{{ $rendezvous->links() }}</div>
        @endif
    @endif

    {{-- Modal Voir --}}
    <x-modal name="show-rdv" max-width="md">
        <div class="p-6" x-show="showRdv">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-semibold text-gray-900">Détails du rendez-vous</h2>
                <button @click="$dispatch('close-modal', 'show-rdv')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <dl class="space-y-3 text-sm">
                <div class="flex gap-3">
                    <dt class="w-28 text-gray-400 shrink-0">Cabinet</dt>
                    <dd class="font-medium text-gray-900" x-text="showRdv?.cabinet"></dd>
                </div>
                <div class="flex gap-3" x-show="showRdv?.opticien">
                    <dt class="w-28 text-gray-400 shrink-0">Opticien</dt>
                    <dd class="font-medium text-gray-900" x-text="showRdv?.opticien"></dd>
                </div>
                <div class="flex gap-3">
                    <dt class="w-28 text-gray-400 shrink-0">Date</dt>
                    <dd class="font-medium text-gray-900" x-text="showRdv?.date_label"></dd>
                </div>
                <div class="flex gap-3">
                    <dt class="w-28 text-gray-400 shrink-0">Type</dt>
                    <dd class="font-medium text-gray-900" x-text="showRdv?.type_label"></dd>
                </div>
                <div class="flex gap-3">
                    <dt class="w-28 text-gray-400 shrink-0">Durée</dt>
                    <dd class="font-medium text-gray-900" x-text="showRdv?.duree + ' min'"></dd>
                </div>
                <div class="flex gap-3">
                    <dt class="w-28 text-gray-400 shrink-0">Statut</dt>
                    <dd>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium border bg-gray-100 text-gray-700 border-gray-200"
                              x-text="showRdv?.statut_label"></span>
                    </dd>
                </div>
                <div class="flex gap-3" x-show="showRdv?.motif">
                    <dt class="w-28 text-gray-400 shrink-0">Motif</dt>
                    <dd class="text-gray-700" x-text="showRdv?.motif"></dd>
                </div>
                <div class="flex gap-3" x-show="showRdv?.notes">
                    <dt class="w-28 text-gray-400 shrink-0">Notes cabinet</dt>
                    <dd class="text-gray-700 italic" x-text="showRdv?.notes"></dd>
                </div>
            </dl>

            <div class="flex justify-end mt-6">
                <x-secondary-button @click="$dispatch('close-modal', 'show-rdv')">Fermer</x-secondary-button>
            </div>
        </div>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-rdv" max-width="md" :show="$errors->isNotEmpty() && old('_modal') === 'edit-rdv'">
        <form method="POST"
              :action="`{{ url('patient/rendezvous') }}/${editRdv?.id}`"
              class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="_modal" value="edit-rdv">

            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-semibold text-gray-900">Modifier le rendez-vous</h2>
                <button type="button" @click="$dispatch('close-modal', 'edit-rdv')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-4">
                La modification n'est possible que pour les rendez-vous en attente de confirmation.
            </p>

            <div class="space-y-4">
                <div>
                    <x-input-label for="edit_date" value="Nouvelle date et heure *"/>
                    <x-text-input id="edit_date" name="date" type="datetime-local" class="mt-1 block w-full"
                                  :value="old('date')"
                                  x-bind:value="editRdv?.date"
                                  min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" required/>
                    <x-input-error :messages="$errors->get('date')" class="mt-1"/>
                </div>

                <div>
                    <x-input-label for="edit_type" value="Type de consultation *"/>
                    <select id="edit_type" name="type" required x-model="editRdv.type"
                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm">
                        @foreach($types as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-1"/>
                </div>

                <div>
                    <x-input-label for="edit_motif" value="Motif (optionnel)"/>
                    <textarea id="edit_motif" name="motif" rows="3" maxlength="500"
                              x-model="editRdv.motif"
                              class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                              placeholder="Décrivez la raison de votre visite..."></textarea>
                    <x-input-error :messages="$errors->get('motif')" class="mt-1"/>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-rdv')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer les modifications</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Annuler --}}
    <x-modal name="cancel-rdv" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Annuler ce rendez-vous ?</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible. Le cabinet sera informé de votre annulation.</p>
            <form method="POST" :action="`{{ url('patient/rendezvous') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'cancel-rdv')">Retour</x-secondary-button>
                    <x-danger-button>Confirmer l'annulation</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-patient-layout>
