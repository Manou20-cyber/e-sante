<x-patient-layout title="Notifications">

<div class="max-w-2xl">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Notifications</h2>
            @php $nonLues = auth()->user()->unreadNotifications->count(); @endphp
            @if($nonLues > 0)
                <p class="text-sm text-gray-500">{{ $nonLues }} non lue(s)</p>
            @endif
        </div>
        @if($nonLues > 0)
            <form method="POST" action="{{ route('patient.notifications.read-all') }}">
                @csrf
                <button type="submit"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-gray-600 font-medium">Aucune notification</p>
            <p class="text-sm text-gray-400 mt-1">Vous serez notifié ici lors de changements importants.</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach($notifications as $notif)
                @php
                    $data = $notif->data;
                    $isUnread = is_null($notif->read_at);
                    $icons = [
                        'rdv' => ['path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'bg' => 'bg-blue-50', 'color' => 'text-blue-500'],
                        'commande' => ['path' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'bg' => 'bg-purple-50', 'color' => 'text-purple-500'],
                        'message' => ['path' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'bg' => 'bg-green-50', 'color' => 'text-green-500'],
                    ];
                    $icon = $icons[$data['type'] ?? 'rdv'] ?? $icons['rdv'];
                @endphp
                <div class="bg-white rounded-xl border {{ $isUnread ? 'border-blue-100 shadow-sm' : 'border-gray-100' }} p-4 flex items-start gap-4 relative">
                    @if($isUnread)
                        <span class="absolute top-4 right-4 w-2 h-2 rounded-full bg-blue-500"></span>
                    @endif

                    <div class="w-10 h-10 {{ $icon['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 {{ $icon['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $data['titre'] ?? 'Notification' }}</p>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $data['message'] ?? '' }}</p>
                        <p class="text-xs text-gray-400 mt-1.5">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex flex-col items-end gap-2 shrink-0">
                        @if(!empty($data['url']))
                            <a href="{{ $data['url'] }}"
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium whitespace-nowrap">
                                Voir →
                            </a>
                        @endif
                        @if($isUnread)
                            <form method="POST" action="{{ route('patient.notifications.read', $notif->id) }}">
                                @csrf
                                <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 whitespace-nowrap">
                                    Marquer lu
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($notifications->hasPages())
            <div class="mt-4">{{ $notifications->links() }}</div>
        @endif
    @endif

</div>

</x-patient-layout>
