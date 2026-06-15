<x-admin-layout title="Messages">

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-900">Messagerie</h2>
        <p class="text-sm text-gray-500">
            {{ $conversations->count() }} conversation(s)
            @if($nonLus > 0)
                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">{{ $nonLus }} non lu(s)</span>
            @endif
        </p>
    </div>
</div>

@if($conversations->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <p class="text-gray-500">Aucun message reçu pour l'instant.</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden divide-y divide-gray-50">
        @foreach($conversations as $interlocuteurId => $dernierMessage)
            @php
                $user = auth()->user();
                $isEnvoye = $dernierMessage->expediteur_id === $user->id;
                $interlocuteur = $isEnvoye ? $dernierMessage->destinataire : $dernierMessage->expediteur;
                $nonLusConv = \App\Models\Message::where('expediteur_id', $interlocuteur->id)
                    ->where('destinataire_id', $user->id)
                    ->whereNull('lu_at')
                    ->count();
            @endphp
            <a href="{{ route('admin.messages.show', $interlocuteur) }}"
               class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition group">

                <div class="w-11 h-11 rounded-full {{ $isEnvoye ? 'bg-blue-100' : 'bg-teal-100' }} flex items-center justify-center font-bold text-lg {{ $isEnvoye ? 'text-blue-700' : 'text-teal-700' }} shrink-0">
                    {{ strtoupper(substr($interlocuteur->name, 0, 1)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-semibold text-sm text-gray-900 truncate">{{ $interlocuteur->name }}</p>
                        <p class="text-xs text-gray-400 shrink-0">{{ $dernierMessage->created_at->diffForHumans() }}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">
                        @if($isEnvoye)<span class="text-gray-400">Vous : </span>@endif
                        {{ Str::limit($dernierMessage->contenu, 60) }}
                    </p>
                </div>

                @if($nonLusConv > 0)
                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                        {{ $nonLusConv }}
                    </span>
                @else
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                @endif
            </a>
        @endforeach
    </div>
@endif

</x-admin-layout>
