<x-admin-layout title="Dossiers médicaux">

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-900">Dossiers médicaux</h2>
        <p class="text-sm text-gray-500">{{ $patients->total() }} patient(s)</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">Patient</th>
                    <th class="px-6 py-3 text-left font-medium">Né(e) le</th>
                    <th class="px-6 py-3 text-left font-medium">RDV</th>
                    <th class="px-6 py-3 text-left font-medium">Consultations</th>
                    <th class="px-6 py-3 text-left font-medium">Dossier</th>
                    <th class="px-6 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0">
                                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $patient->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $patient->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-gray-600">{{ $patient->date_naissance?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $patient->rendezvous_count }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $patient->consultations_count }}</td>
                        <td class="px-6 py-3">
                            @if($patient->dossierMedical)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-medium">Renseigné</span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Vide</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.dossiers.show', $patient) }}"
                               class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded-lg hover:bg-blue-50 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir le dossier
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">Aucun patient trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $patients->links() }}</div>
    @endif
</div>

</x-admin-layout>
