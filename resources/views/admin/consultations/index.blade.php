<x-admin-layout title="Consultations">

@php $isSuperAdmin = auth()->user()->hasRole('super_admin'); @endphp

<div x-data="{
    editItem: {},
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-consultation') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-consultation') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Consultations</h2>
            <p class="text-sm text-gray-500">{{ $consultations->total() }} consultation(s)</p>
        </div>
        @unless($isSuperAdmin)
            <button @click="$dispatch('open-modal', 'create-consultation')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle consultation
            </button>
        @else
            <span class="text-xs px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg font-medium">Vue lecture seule</span>
        @endunless
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Patient</th>
                        <th class="px-6 py-3 text-left font-medium">Cabinet</th>
                        <th class="px-6 py-3 text-left font-medium">Médecin</th>
                        <th class="px-6 py-3 text-left font-medium">Date</th>
                        <th class="px-6 py-3 text-left font-medium">Type</th>
                        <th class="px-6 py-3 text-left font-medium">Montant</th>
                        @unless($isSuperAdmin)
                            <th class="px-6 py-3 text-left font-medium">Actions</th>
                        @endunless
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($consultations as $consultation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $consultation->patient->user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $consultation->cabinet->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $consultation->medecin->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $consultation->date->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $consultation->type }}</td>
                            <td class="px-6 py-3 text-gray-900 font-medium">
                                {{ $consultation->montant ? number_format($consultation->montant, 0, ',', ' ').' XAF' : '—' }}
                            </td>
                            @unless($isSuperAdmin)
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <button @click="setEdit({{ $consultation }})"
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button @click="setDelete({{ $consultation->id }})"
                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endunless
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isSuperAdmin ? 6 : 7 }}" class="px-6 py-10 text-center text-gray-400">Aucune consultation</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($consultations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $consultations->links() }}</div>
        @endif
    </div>

    @unless($isSuperAdmin)
        @php $selectClass = 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'; @endphp

        {{-- Modal Créer --}}
        <x-modal name="create-consultation" max-width="lg">
            <form method="POST" action="{{ route('admin.consultations.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouvelle consultation</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="cs_patient" value="Patient *"/>
                        <select id="cs_patient" name="patient_id" required class="{{ $selectClass }}">
                            <option value="">-- Sélectionner --</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="cs_cabinet" value="Cabinet *"/>
                        <select id="cs_cabinet" name="cabinet_id" required class="{{ $selectClass }}">
                            <option value="">-- Sélectionner --</option>
                            @foreach($cabinets as $c)
                                <option value="{{ $c->id }}">{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="cs_medecin" value="Médecin/Opticien *"/>
                        <select id="cs_medecin" name="medecin_id" required class="{{ $selectClass }}">
                            <option value="">-- Sélectionner --</option>
                            @foreach($medecins as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="cs_date" value="Date *"/>
                        <x-text-input id="cs_date" name="date" type="datetime-local" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label for="cs_type" value="Type *"/>
                        <x-text-input id="cs_type" name="type" value="bilan_visuel" class="mt-1 block w-full" required/>
                    </div>
                    <div>
                        <x-input-label for="cs_montant" value="Montant (XAF)"/>
                        <x-text-input id="cs_montant" name="montant" type="number" step="0.01" min="0" class="mt-1 block w-full"/>
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="cs_diagnostic" value="Diagnostic"/>
                        <textarea id="cs_diagnostic" name="diagnostic" rows="2"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-consultation')">Annuler</x-secondary-button>
                    <x-primary-button>Créer</x-primary-button>
                </div>
            </form>
        </x-modal>

        {{-- Modal Modifier --}}
        <x-modal name="edit-consultation" max-width="lg">
            <form method="POST" :action="`{{ url('dashboard/consultations') }}/${editItem?.id}`" class="p-6">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier la consultation</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Patient *"/>
                        <select name="patient_id" required x-model="editItem.patient_id" class="{{ $selectClass }}">
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Cabinet *"/>
                        <select name="cabinet_id" required x-model="editItem.cabinet_id" class="{{ $selectClass }}">
                            @foreach($cabinets as $c)
                                <option value="{{ $c->id }}">{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Médecin *"/>
                        <select name="medecin_id" required x-model="editItem.medecin_id" class="{{ $selectClass }}">
                            @foreach($medecins as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Date *"/>
                        <x-text-input name="date" type="datetime-local" class="mt-1 block w-full" x-model="editItem.date" required/>
                    </div>
                    <div>
                        <x-input-label value="Type *"/>
                        <x-text-input name="type" class="mt-1 block w-full" x-model="editItem.type" required/>
                    </div>
                    <div>
                        <x-input-label value="Montant (XAF)"/>
                        <x-text-input name="montant" type="number" step="0.01" min="0" class="mt-1 block w-full" x-model="editItem.montant"/>
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label value="Diagnostic"/>
                        <textarea name="diagnostic" rows="2" x-model="editItem.diagnostic"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-consultation')">Annuler</x-secondary-button>
                    <x-primary-button>Enregistrer</x-primary-button>
                </div>
            </form>
        </x-modal>

        {{-- Modal Supprimer --}}
        <x-modal name="delete-consultation" max-width="sm">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer la consultation</h2>
                <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
                <form method="POST" :action="`{{ url('dashboard/consultations') }}/${deleteId}`">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-3">
                        <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-consultation')">Annuler</x-secondary-button>
                        <x-danger-button>Supprimer</x-danger-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endunless

</div>
</x-admin-layout>
