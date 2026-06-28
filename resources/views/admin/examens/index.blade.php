<x-admin-layout title="Résultats d'examens optiques">

@php $isSuperAdmin = auth()->user()->hasRole('super_admin'); @endphp

<div x-data="{
    showCreate: false,
    editData: null,
    deleteId: null,
    setEdit(examen) { this.editData = examen; },
    setDelete(id) { this.deleteId = id; }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Résultats d'examens optiques</h2>
            <p class="text-sm text-gray-500">{{ $examens->total() }} examen(s)</p>
        </div>
        @unless($isSuperAdmin)
            <button @click="showCreate = true"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvel examen
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
                        <th class="px-6 py-3 text-left font-medium">Consultation</th>
                        <th class="px-6 py-3 text-left font-medium">Acuité OD</th>
                        <th class="px-6 py-3 text-left font-medium">Acuité OG</th>
                        <th class="px-6 py-3 text-left font-medium">Tension OD</th>
                        <th class="px-6 py-3 text-left font-medium">Tension OG</th>
                        <th class="px-6 py-3 text-left font-medium">Date</th>
                        @unless($isSuperAdmin)
                            <th class="px-6 py-3 text-left font-medium">Actions</th>
                        @endunless
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($examens as $examen)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $examen->patient?->user->name ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-gray-600">
                                @if($examen->consultation)
                                    <span class="text-xs">{{ $examen->consultation->type }}</span><br>
                                    <span class="text-xs text-gray-400">{{ $examen->consultation->date->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $examen->acuite_od ?? '—' }}/10</td>
                            <td class="px-6 py-3 text-gray-600">{{ $examen->acuite_og ?? '—' }}/10</td>
                            <td class="px-6 py-3 text-gray-600">{{ $examen->tension_od ? $examen->tension_od.' mmHg' : '—' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $examen->tension_og ? $examen->tension_og.' mmHg' : '—' }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $examen->created_at->format('d/m/Y') }}</td>
                            @unless($isSuperAdmin)
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-1">
                                    <button @click="setEdit({
                                        id: {{ $examen->id }},
                                        consultation_id: {{ $examen->consultation_id ?? 'null' }},
                                        patient_id: {{ $examen->patient_id ?? 'null' }},
                                        acuite_od: '{{ $examen->acuite_od }}',
                                        acuite_og: '{{ $examen->acuite_og }}',
                                        acuite_od_corrigee: '{{ $examen->acuite_od_corrigee }}',
                                        acuite_og_corrigee: '{{ $examen->acuite_og_corrigee }}',
                                        tension_od: '{{ $examen->tension_od }}',
                                        tension_og: '{{ $examen->tension_og }}',
                                        observations: @js($examen->observations)
                                    })"
                                            class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="setDelete({{ $examen->id }})"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
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
                            <td colspan="{{ $isSuperAdmin ? 7 : 8 }}" class="px-6 py-10 text-center text-gray-400">Aucun examen enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($examens->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $examens->links() }}</div>
        @endif
    </div>

    @unless($isSuperAdmin)

    {{-- Modal Créer --}}
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
        <div @click.outside="showCreate = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <form method="POST" action="{{ route('admin.examens.store') }}">
                @csrf
                <div class="px-6 pt-6 pb-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Nouvel examen optique</h2>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="c_patient_id" value="Patient *"/>
                            <select id="c_patient_id" name="patient_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">— Sélectionner —</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-1"/>
                        </div>

                        <div>
                            <x-input-label for="c_consultation_id" value="Consultation associée (optionnel)"/>
                            <select id="c_consultation_id" name="consultation_id"
                                    class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">— Aucune —</option>
                                @foreach($consultations as $c)
                                    <option value="{{ $c->id }}" {{ old('consultation_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->patient?->user->name }} — {{ $c->type }} ({{ $c->date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $champsExamen = [
                                    ['acuite_od','Acuité OD (sans correction)'],
                                    ['acuite_og','Acuité OG (sans correction)'],
                                    ['acuite_od_corrigee','Acuité OD (corrigée)'],
                                    ['acuite_og_corrigee','Acuité OG (corrigée)'],
                                    ['tension_od','Tension OD (mmHg)'],
                                    ['tension_og','Tension OG (mmHg)'],
                                ];
                            @endphp
                            @foreach($champsExamen as [$name, $label])
                                <div>
                                    <x-input-label value="{{ $label }}"/>
                                    <x-text-input name="{{ $name }}" type="number" step="0.1"
                                                  class="mt-1 block w-full" :value="old($name)" placeholder="—"/>
                                    <x-input-error :messages="$errors->get($name)" class="mt-1"/>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <x-input-label for="c_observations" value="Observations"/>
                            <textarea id="c_observations" name="observations" rows="3"
                                      class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm">{{ old('observations') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
                    <x-secondary-button type="button" @click="showCreate = false">Annuler</x-secondary-button>
                    <x-primary-button>Enregistrer</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Modifier --}}
    <div x-show="editData !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
        <div @click.outside="editData = null"
             class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <template x-if="editData">
                <form method="POST" :action="`{{ url('dashboard/examens') }}/${editData.id}`">
                    @csrf
                    @method('PUT')
                    <div class="px-6 pt-6 pb-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-5">Modifier l'examen</h2>

                        <div class="grid grid-cols-2 gap-3">
                            @foreach($champsExamen as [$name, $label])
                                <div>
                                    <label class="text-sm font-medium text-gray-700">{{ $label }}</label>
                                    <input name="{{ $name }}" type="number" step="0.1"
                                           :value="editData.{{ $name }}"
                                           class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                                           placeholder="—"/>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label class="text-sm font-medium text-gray-700">Observations</label>
                            <textarea name="observations" rows="3" x-text="editData.observations"
                                      class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
                        <x-secondary-button type="button" @click="editData = null">Annuler</x-secondary-button>
                        <x-primary-button>Mettre à jour</x-primary-button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    {{-- Modal Supprimer --}}
    <div x-show="deleteId !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
        <div @click.outside="deleteId = null" class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer l'examen ?</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
            <div class="flex justify-end gap-3">
                <x-secondary-button type="button" @click="deleteId = null">Annuler</x-secondary-button>
                <form method="POST" :action="`{{ url('dashboard/examens') }}/${deleteId}`">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    @endunless

</div>
</x-admin-layout>
