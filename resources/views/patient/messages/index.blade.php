<x-patient-layout title="Messagerie">

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-900">Messagerie</h2>
        <p class="text-sm text-gray-500">{{ $conversations->count() }} conversation(s)</p>
    </div>
    <button @click="$dispatch('open-modal', 'new-message')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau message
    </button>
</div>

@if($conversations->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-medium">Aucune conversation</p>
        <p class="text-sm text-gray-400 mt-1">Contactez un cabinet en cliquant sur "Nouveau message".</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden divide-y divide-gray-50">
        @foreach($conversations as $cabinetId => $dernierMessage)
            @php
                $cabinet = $dernierMessage->cabinet;
                $nonLus = \App\Models\Message::where('cabinet_id', $cabinetId)
                    ->where('destinataire_id', auth()->id())
                    ->whereNull('lu_at')
                    ->count();
                $isEnvoye = $dernierMessage->expediteur_id === auth()->id();
            @endphp
            @if($cabinet)
                <a href="{{ route('patient.messages.show', $cabinet->uuid) }}"
                   class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition group">

                    <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center shrink-0 font-bold text-blue-700 text-lg group-hover:bg-blue-200 transition">
                        {{ strtoupper(substr($cabinet->nom, 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold text-sm text-gray-900 truncate">{{ $cabinet->nom }}</p>
                            <p class="text-xs text-gray-400 shrink-0">{{ $dernierMessage->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">
                            @if($isEnvoye)<span class="text-gray-400">Vous : </span>@endif
                            {{ Str::limit($dernierMessage->contenu, 60) }}
                        </p>
                    </div>

                    @if($nonLus > 0)
                        <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                            {{ $nonLus }}
                        </span>
                    @else
                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                </a>
            @endif
        @endforeach
    </div>
@endif

{{-- Modal nouveau message --}}
<x-modal name="new-message" max-width="lg">
    <form method="POST" action="{{ route('patient.messages.store') }}" class="p-6">
        @csrf
        <h2 class="text-lg font-semibold text-gray-900 mb-5">Nouveau message</h2>
        <div class="space-y-4">
            <div>
                <x-input-label for="msg_cabinet" value="Cabinet destinataire *"/>
                <select id="msg_cabinet" name="cabinet_id" required
                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm">
                    <option value="">-- Sélectionner un cabinet --</option>
                    @foreach($cabinets as $cabinet)
                        <option value="{{ $cabinet->id }}">{{ $cabinet->nom }} — {{ $cabinet->ville }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('cabinet_id')" class="mt-1"/>
            </div>
            <div>
                <x-input-label for="msg_objet" value="Objet *"/>
                <x-text-input id="msg_objet" name="objet" class="mt-1 block w-full" required
                              placeholder="Ex: Question sur mon ordonnance"/>
                <x-input-error :messages="$errors->get('objet')" class="mt-1"/>
            </div>
            <div>
                <x-input-label for="msg_contenu" value="Message *"/>
                <textarea id="msg_contenu" name="contenu" rows="4" required maxlength="2000"
                          class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm"
                          placeholder="Rédigez votre message ici..."></textarea>
                <x-input-error :messages="$errors->get('contenu')" class="mt-1"/>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <x-secondary-button type="button" @click="$dispatch('close-modal', 'new-message')">Annuler</x-secondary-button>
            <x-primary-button>Envoyer</x-primary-button>
        </div>
    </form>
</x-modal>

</x-patient-layout>
