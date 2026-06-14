<x-patient-layout title="Mes documents">

<div x-data="{}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Mes documents</h2>
            <p class="text-sm text-gray-500">{{ $documents->total() }} document(s)</p>
        </div>
        <button @click="$dispatch('open-modal', 'upload-doc')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Ajouter un document
        </button>
    </div>

    @if($documents->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
            </div>
            <p class="text-gray-600 font-medium">Aucun document</p>
            <p class="text-sm text-gray-400 mt-1">Téléchargez vos ordonnances, résultats et certificats.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($documents as $document)
                @php
                    $typeColors = ['ordonnance' => 'bg-blue-100 text-blue-700', 'resultat' => 'bg-green-100 text-green-700', 'certificat' => 'bg-purple-100 text-purple-700', 'facture' => 'bg-yellow-100 text-yellow-700', 'autre' => 'bg-gray-100 text-gray-600'];
                    $typeIcons = ['ordonnance' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'default' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'];
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl {{ $typeColors[$document->type] ?? 'bg-gray-100 text-gray-600' }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="{{ $typeIcons[$document->type] ?? $typeIcons['default'] }}"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $document->nom }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-1.5 py-0.5 rounded-full {{ $typeColors[$document->type] ?? 'bg-gray-100 text-gray-600' }} font-medium">
                                {{ ucfirst($document->type) }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $document->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($document->description)
                            <p class="text-xs text-gray-400 mt-1 truncate">{{ $document->description }}</p>
                        @endif
                    </div>
                    <a href="{{ route('patient.documents.download', $document) }}"
                       class="shrink-0 p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                       title="Télécharger">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>
        @if($documents->hasPages())
            <div class="mt-4">{{ $documents->links() }}</div>
        @endif
    @endif

    {{-- Modal upload --}}
    <x-modal name="upload-doc" max-width="lg">
        <form method="POST" action="{{ route('patient.documents.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Ajouter un document</h2>
            <div class="space-y-4">
                <div>
                    <x-input-label for="doc_nom" value="Nom du document *"/>
                    <x-text-input id="doc_nom" name="nom" class="mt-1 block w-full" required
                                  placeholder="Ex: Ordonnance Dr. Dupont - Jan 2025"/>
                    <x-input-error :messages="$errors->get('nom')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="doc_type" value="Type *"/>
                    <select id="doc_type" name="type" required
                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm">
                        <option value="ordonnance">Ordonnance</option>
                        <option value="resultat">Résultat d'examen</option>
                        <option value="certificat">Certificat médical</option>
                        <option value="facture">Facture</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="doc_fichier" value="Fichier * (PDF, image, doc — max 10 Mo)"/>
                    <input id="doc_fichier" name="fichier" type="file" required
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    <x-input-error :messages="$errors->get('fichier')" class="mt-1"/>
                </div>
                <div>
                    <x-input-label for="doc_desc" value="Description (optionnel)"/>
                    <x-text-input id="doc_desc" name="description" class="mt-1 block w-full" placeholder="Informations complémentaires..."/>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button type="button" @click="$dispatch('close-modal', 'upload-doc')">Annuler</x-secondary-button>
                <x-primary-button>Enregistrer</x-primary-button>
            </div>
        </form>
    </x-modal>

</div>
</x-patient-layout>
