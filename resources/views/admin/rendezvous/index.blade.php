<x-admin-layout title="Rendez-vous">

<div x-data="{
    editItem: null,
    deleteId: null,
    setEdit(item) { this.editItem = {...item}; $dispatch('open-modal', 'edit-rdv') },
    setDelete(id) { this.deleteId = id; $dispatch('open-modal', 'delete-rdv') }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Rendez-vous</h2>
            <p class="text-sm text-gray-500">{{ $rendezvous->total() }} rendez-vous</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-rdv')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau rendez-vous
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Patient</th>
                        <th class="px-6 py-3 text-left font-medium">Cabinet</th>
                        <th class="px-6 py-3 text-left font-medium">Date</th>
                        <th class="px-6 py-3 text-left font-medium">Type</th>
                        <th class="px-6 py-3 text-left font-medium">Durée</th>
                        <th class="px-6 py-3 text-left font-medium">Statut</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rendezvous as $rdv)
                        @php
                            $statusColors = [
                                'en_attente' => 'bg-yellow-100 text-yellow-700',
                                'confirme' => 'bg-blue-100 text-blue-700',
                                'termine' => 'bg-green-100 text-green-700',
                                'annule' => 'bg-red-100 text-red-700',
                                'absent' => 'bg-gray-100 text-gray-600',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $rdv->patient->user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $rdv->cabinet->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $rdv->date->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $rdv->type }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $rdv->duree }} min</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$rdv->statut] ?? '' }}">
                                    {{ str_replace('_', ' ', $rdv->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <button @click="setEdit({{ $rdv }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="setDelete({{ $rdv->id }})"
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
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400">Aucun rendez-vous</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rendezvous->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $rendezvous->links() }}</div>
        @endif
    </div>

    {{-- Modal Créer --}}
    <x-modal name="create-rdv" max-width="lg">
        <form method="POST" action="{{ route('admin.rendezvous.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nouveau rendez-vous</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="rdv_patient" value="Patient *"/>
                    <select id="rdv_patient" name="patient_id" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sélectionner --</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="rdv_cabinet" value="Cabinet *"/>
                    <select id="rdv_cabinet" name="cabinet_id" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Sélectionner --</option>
                        @foreach($cabinets as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="rdv_date" value="Date et heure *"/>
                    <x-text-input id="rdv_date" name="date" type="datetime-local" class="mt-1 block w-full" required/>
                </div>
                <div>
                    <x-input-label for="rdv_duree" value="Durée (min) *"/>
                    <x-text-input id="rdv_duree" name="duree" type="number" value="30" min="15" max="180" class="mt-1 block w-full" required/>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="rdv_type" value="Type *"/>
                    <x-text-input id="rdv_type" name="type" value="consultation" class="mt-1 block w-full" required/>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="rdv_motif" value="Motif"/>
                    <textarea id="rdv_motif" name="motif" rows="2"
                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'create-rdv')">Annuler</x-secondary-button>
                <x-primary-button>Créer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Modifier --}}
    <x-modal name="edit-rdv" max-width="lg">
        <form method="POST" :action="`{{ url('admin/rendezvous') }}/${editItem?.id}`" class="p-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Modifier le rendez-vous</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Patient *"/>
                    <select name="patient_id" required x-model="editItem.patient_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Cabinet *"/>
                    <select name="cabinet_id" required x-model="editItem.cabinet_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach($cabinets as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Date et heure *"/>
                    <x-text-input name="date" type="datetime-local" class="mt-1 block w-full" x-model="editItem.date" required/>
                </div>
                <div>
                    <x-input-label value="Durée (min) *"/>
                    <x-text-input name="duree" type="number" min="15" max="180" class="mt-1 block w-full" x-model="editItem.duree" required/>
                </div>
                <div>
                    <x-input-label value="Type *"/>
                    <x-text-input name="type" class="mt-1 block w-full" x-model="editItem.type" required/>
                </div>
                <div>
                    <x-input-label value="Statut *"/>
                    <select name="statut" required x-model="editItem.statut"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="en_attente">En attente</option>
                        <option value="confirme">Confirmé</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label value="Motif"/>
                    <textarea name="motif" rows="2" x-model="editItem.motif"
                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-rdv')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Supprimer --}}
    <x-modal name="delete-rdv" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Supprimer le rendez-vous</h2>
            <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
            <form method="POST" :action="`{{ url('admin/rendezvous') }}/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'delete-rdv')">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer</x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
</x-admin-layout>
