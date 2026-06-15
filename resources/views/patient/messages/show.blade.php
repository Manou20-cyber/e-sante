<x-patient-layout title="Conversation — {{ $cabinet->nom }}">

{{-- Header de la conversation --}}
<div class="flex items-center gap-4 mb-4">
    <a href="{{ route('patient.messages.index') }}"
       class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center font-bold text-blue-700 text-lg">
            {{ strtoupper(substr($cabinet->nom, 0, 1)) }}
        </div>
        <div>
            <p class="font-semibold text-gray-900 text-sm">{{ $cabinet->nom }}</p>
            <p class="text-xs text-gray-500">{{ $cabinet->ville }} · {{ $cabinet->telephone }}</p>
        </div>
    </div>
</div>

{{-- Fil de la conversation --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col"
     style="height: calc(100vh - 280px); min-height: 400px;">

    {{-- Messages --}}
    <div id="chat-messages" class="flex-1 overflow-y-auto px-4 py-5 space-y-4"
         x-data="{}" x-init="$el.scrollTop = $el.scrollHeight">

        @if($thread->isEmpty())
            <div class="flex items-center justify-center h-full text-gray-400 text-sm">
                Aucun message. Soyez le premier à écrire !
            </div>
        @else
            @foreach($thread as $message)
                @php $isMine = $message->expediteur_id === auth()->id(); @endphp

                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2">

                    {{-- Avatar interlocuteur --}}
                    @if(!$isMine)
                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0 mb-1">
                            {{ strtoupper(substr($message->expediteur->name, 0, 1)) }}
                        </div>
                    @endif

                    {{-- Bulle --}}
                    <div class="max-w-xs sm:max-w-md lg:max-w-lg">
                        {{-- Nom + heure --}}
                        <div class="flex items-center gap-2 mb-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <span class="text-xs text-gray-400">
                                @if(!$isMine){{ $message->expediteur->name }} · @endif
                                {{ $message->created_at->format('d/m H:i') }}
                            </span>
                        </div>

                        {{-- Objet --}}
                        @if($message->objet && !str_starts_with($message->objet, 'Re:'))
                            <div class="text-xs font-semibold {{ $isMine ? 'text-blue-200 text-right' : 'text-gray-500' }} mb-1">
                                📎 {{ $message->objet }}
                            </div>
                        @endif

                        {{-- Contenu --}}
                        <div class="{{ $isMine
                                ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm'
                                : 'bg-gray-100 text-gray-900 rounded-2xl rounded-bl-sm' }} px-4 py-3 text-sm leading-relaxed break-words">
                            {{ $message->contenu }}
                        </div>

                        {{-- Lu --}}
                        @if($isMine && $message->lu_at)
                            <div class="text-right mt-0.5">
                                <span class="text-xs text-blue-400">✓ Lu</span>
                            </div>
                        @endif
                    </div>

                    {{-- Avatar patient (moi) --}}
                    @if($isMine)
                        <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white shrink-0 mb-1">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    {{-- Zone de saisie --}}
    <div class="border-t border-gray-100 px-4 py-3 bg-gray-50 rounded-b-2xl">
        <form method="POST"
              action="{{ route('patient.messages.repondre', $cabinet->uuid) }}"
              x-data="{ msg: '', sending: false }"
              @submit="sending = true">
            @csrf

            <div class="flex items-end gap-3">
                <textarea name="contenu"
                          x-model="msg"
                          rows="1"
                          required
                          maxlength="2000"
                          placeholder="Votre message..."
                          @keydown.enter.exact.prevent="if(msg.trim()) { sending = true; $el.closest('form').submit() }"
                          @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                          class="flex-1 resize-none border-0 bg-white rounded-xl px-4 py-3 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none max-h-32 overflow-y-auto"
                          style="min-height: 44px;"></textarea>

                <button type="submit"
                        :disabled="!msg.trim() || sending"
                        :class="msg.trim() && !sending ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-200 cursor-not-allowed'"
                        class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition">
                    <svg class="w-5 h-5" :class="msg.trim() && !sending ? 'text-white' : 'text-gray-400'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>

            <p class="text-xs text-gray-400 mt-1.5">
                Appuyez sur <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Entrée</kbd> pour envoyer,
                <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Shift+Entrée</kbd> pour un saut de ligne.
            </p>
        </form>
    </div>

</div>

{{-- Scroll vers le bas à l'arrivée --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('chat-messages');
        if (el) { el.scrollTop = el.scrollHeight; }
    });
</script>

</x-patient-layout>
