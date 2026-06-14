<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RepondreMessageRequest;
use App\Models\CabinetOptique;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NouveauMessageRecu;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageAdminController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $conversations = Message::where(function ($q) use ($user) {
            $q->where('expediteur_id', $user->id)->orWhere('destinataire_id', $user->id);
        })
            ->with(['expediteur', 'destinataire'])
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn (Message $m) => $m->expediteur_id === $user->id ? $m->destinataire_id : $m->expediteur_id)
            ->map(fn ($msgs) => $msgs->first());

        $nonLus = Message::where('destinataire_id', $user->id)->whereNull('lu_at')->count();

        return view('admin.messages.index', compact('conversations', 'nonLus'));
    }

    public function show(User $interlocuteur): View
    {
        $user = auth()->user();

        $thread = Message::where(function ($q) use ($user, $interlocuteur) {
            $q->where('expediteur_id', $user->id)->where('destinataire_id', $interlocuteur->id);
        })
            ->orWhere(function ($q) use ($user, $interlocuteur) {
                $q->where('expediteur_id', $interlocuteur->id)->where('destinataire_id', $user->id);
            })
            ->with(['expediteur', 'destinataire'])
            ->orderBy('created_at')
            ->get();

        $thread->each(function (Message $m) use ($user) {
            if ($m->destinataire_id === $user->id && is_null($m->lu_at)) {
                $m->update(['lu_at' => now()]);
            }
        });

        return view('admin.messages.show', compact('interlocuteur', 'thread'));
    }

    public function repondre(RepondreMessageRequest $request, User $interlocuteur): RedirectResponse
    {
        $cabinet = auth()->user()->cabinetOptique
            ?? ($auth = auth()->user()->cabinet_id ? CabinetOptique::find(auth()->user()->cabinet_id) : null);

        Message::create([
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $interlocuteur->id,
            'cabinet_id' => $cabinet?->id ?? auth()->user()->cabinet_id,
            'objet' => null,
            'contenu' => $request->contenu,
        ]);

        if ($interlocuteur->hasRole('patient')) {
            $interlocuteur->notify(new NouveauMessageRecu(auth()->user(), $cabinet));
        }

        return back();
    }
}
