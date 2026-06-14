<x-admin-layout title="Gestion des retours">

@php
    $statutLabels = [
        'en_attente' => 'En attente',
        'approuve'   => 'Approuvé',
        'refuse'     => 'Refusé',
        'traite'     => 'Traité',
    ];
    $statutColors = [
        'en_attente' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'approuve'   => 'bg-blue-100 text-blue-700 border-blue-200',
        'refuse'     => 'bg-red-100 text-red-700 border-red-200',
        'traite'     => 'bg-green-100 text-green-700 border-green-200',
    ];
    $filtreActuel = request('statut', '');
@endphp

<div x-data="{
    retour: null,
    open(item) { this.retour = item; $dispatch('open-modal', 'traiter-retour') }
}">

    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Retours commandes</h2>
            <p class="text-sm text-gray-500">{{ $retours->total() }} retour(s) au total</p>
        </div>
    </div>

    {{-- Cartes stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @foreach(['en_attente' => ['label' => 'En attente', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50', 'border' => 'border-yellow-200'],
                  'approuve'   => ['label' => 'Approuvés',  'color' => 'text-blue-600',   'bg' => 'bg-blue-50',   'border' => 'border-blue-200'],
                  'refuse'     => ['label' => 'Refusés',    'color' => 'text-red-600',    'bg' => 'bg-red-50',    'border' => 'border-red-200'],
                  'traite'     => ['label' => 'Traités',    'color' => 'text-green-600',  'bg' => 'bg-green-50',  'border' => 'border-green-200'],
                ] as $key => $s)
            <a href="{{ route('admin.retours.index', ['statut' => $filtreActuel === $key ? '' : $key]) }}"
               class="rounded-xl border p-4 {{ $s['bg'] }} {{ $s['border'] }} {{ $filtreActuel === $key ? 'ring-2 ring-offset-1 ring-gray-400' : 'hover:shadow-sm' }} transition">
                <p class="text-2xl font-bold {{ $s['color'] }}">{{ $stats[$key] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $s['label'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Filtres statut --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.retours.index') }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium border transition
                  {{ $filtreActuel === '' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">
            Tous
        </a>
        @foreach($statutLabels as $key => $label)
            <a href="{{ route('admin.retours.index', ['statut' => $key]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium border transition
                      {{ $filtreActuel === $key ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Commande</th>
                        <th class="px-5 py-3 text-left font-medium">Patient</th>
                        <th class="px-5 py-3 text-left font-medium">Cabinet</th>
                        <th class="px-5 py-3 text-left font-medium">Raison</th>
                        <th class="px-5 py-3 text-left font-medium">Statut</th>
                        <th class="px-5 py-3 text-left font-medium">Remboursement</th>
                        <th class="px-5 py-3 text-left font-medium">Date</th>
                        <th class="px-5 py-3 text-left font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($retours as $retour)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <span class="font-mono text-xs text-gray-700 bg-gray-100 px-2 py-0.5 rounded">
                                    {{ $retour->commande->numero }}
                                </span>
                                @if($retour->commande->produits->isNotEmpty())
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $retour->commande->produits->take(2)->pluck('libelle')->join(', ') }}
                                        @if($retour->commande->produits->count() > 2)
                                            +{{ $retour->commande->produits->count() - 2 }}
                                        @endif
                                    </p>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-gray-900">
                                {{ $retour->patient->user->name }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 text-xs">
                                {{ $retour->commande->cabinet->nom }}
                            </td>
                            <td class="px-5 py-3 max-w-xs">
                                <p class="text-gray-700 text-xs line-clamp-2">{{ $retour->raison }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium border {{ $statutColors[$retour->statut] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                    {{ $statutLabels[$retour->statut] ?? $retour->statut }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                @if($retour->montant_rembourse !== null)
                                    <span class="text-sm font-semibold text-green-700">
                                        {{ number_format($retour->montant_rembourse, 0, ',', ' ') }} XAF
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-400 text-xs">
                                {{ $retour->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3">
                                <button @click="open({{ json_encode([
                                    'id'               => $retour->id,
                                    'statut'           => $retour->statut,
                                    'raison'           => $retour->raison,
                                    'notes_cabinet'    => $retour->notes_cabinet ?? '',
                                    'montant_rembourse'=> $retour->montant_rembourse,
                                    'patient'          => $retour->patient->user->name,
                                    'commande'         => $retour->commande->numero,
                                    'montant_commande' => $retour->commande->montant_total,
                                ]) }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border
                                               {{ $retour->statut === 'en_attente' ? 'border-orange-200 text-orange-700 bg-orange-50 hover:bg-orange-100' : 'border-gray-200 text-gray-600 bg-white hover:bg-gray-50' }}
                                               transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ $retour->statut === 'en_attente' ? 'Traiter' : 'Modifier' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    <p class="text-sm">Aucun retour{{ $filtreActuel ? ' pour ce statut' : '' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($retours->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $retours->links() }}</div>
        @endif
    </div>

    {{-- Modal traitement --}}
    <x-modal name="traiter-retour" max-width="lg">
        <div class="p-6" x-show="retour">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Traiter le retour</h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Commande <span class="font-mono" x-text="retour?.commande"></span>
                        — <span x-text="retour?.patient"></span>
                    </p>
                </div>
            </div>

            {{-- Raison patient --}}
            <div class="mb-5 rounded-xl bg-orange-50 border border-orange-100 px-4 py-3">
                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide mb-1">Raison du retour (patient)</p>
                <p class="text-sm text-gray-700" x-text="retour?.raison"></p>
            </div>

            <form method="POST" :action="`{{ url('dashboard/retours') }}/${retour?.id}`">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Statut --}}
                    <div>
                        <x-input-label value="Décision *"/>
                        <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(['en_attente' => 'En attente', 'approuve' => 'Approuver', 'refuse' => 'Refuser', 'traite' => 'Marquer traité'] as $val => $lbl)
                                <label class="cursor-pointer">
                                    <input type="radio" name="statut" value="{{ $val }}"
                                           x-bind:checked="retour?.statut === '{{ $val }}'"
                                           class="sr-only peer"/>
                                    <div class="text-center text-xs font-medium py-2 px-1 rounded-lg border-2 transition
                                                peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white
                                                border-gray-200 text-gray-600 hover:border-gray-400 cursor-pointer">
                                        {{ $lbl }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Montant remboursé --}}
                    <div>
                        <x-input-label value="Montant remboursé (XAF)"/>
                        <div class="mt-1 relative">
                            <x-text-input name="montant_rembourse" type="number" min="0" step="1"
                                          x-bind:value="retour?.montant_rembourse ?? ''"
                                          class="block w-full pr-16"
                                          placeholder="Laisser vide si aucun remboursement"/>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">XAF</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            Montant commande :
                            <span class="font-medium text-gray-600"
                                  x-text="retour ? new Intl.NumberFormat('fr-FR').format(retour.montant_commande) + ' XAF' : ''"></span>
                        </p>
                    </div>

                    {{-- Notes cabinet --}}
                    <div>
                        <x-input-label value="Notes / Réponse au patient"/>
                        <textarea name="notes_cabinet" rows="4"
                                  x-bind:value="retour?.notes_cabinet"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                  placeholder="Expliquez votre décision au patient, les prochaines étapes..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'traiter-retour')">
                        Annuler
                    </x-secondary-button>
                    <x-primary-button>Enregistrer</x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

</div>

</x-admin-layout>
