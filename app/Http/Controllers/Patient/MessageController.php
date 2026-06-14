<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreMessageRequest;
use App\Models\CabinetOptique;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Une conversation par cabinet = dernier message de chaque fil
        $conversations = Message::where(function ($q) use ($user) {
            $q->where('expediteur_id', $user->id)
                ->orWhere('destinataire_id', $user->id);
        })
            ->with(['cabinet', 'expediteur', 'destinataire'])
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('cabinet_id')
            ->map(fn ($msgs) => $msgs->first());

        $cabinets = CabinetOptique::where('est_actif', true)->get();

        return view('patient.messages.index', compact('conversations', 'cabinets'));
    }

    public function show(CabinetOptique $cabinet): View
    {
        $user = auth()->user();

        $thread = Message::where('cabinet_id', $cabinet->id)
            ->where(function ($q) use ($user) {
                $q->where('expediteur_id', $user->id)
                    ->orWhere('destinataire_id', $user->id);
            })
            ->with(['expediteur', 'destinataire'])
            ->orderBy('created_at')
            ->get();

        // Marquer tous les messages reçus comme lus
        $thread->each(function (Message $message) use ($user) {
            if ($message->destinataire_id === $user->id && is_null($message->lu_at)) {
                $message->update(['lu_at' => now()]);
            }
        });

        return view('patient.messages.show', compact('cabinet', 'thread'));
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $cabinet = CabinetOptique::findOrFail($request->cabinet_id);

        Message::create([
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $cabinet->user_id,
            'cabinet_id' => $cabinet->id,
            'objet' => $request->objet,
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('patient.messages.show', $cabinet->uuid)
            ->with('success', 'Message envoyé.');
    }

    public function repondre(CabinetOptique $cabinet): RedirectResponse
    {
        $contenu = request()->validate([
            'contenu' => ['required', 'string', 'min:2', 'max:2000'],
        ])['contenu'];

        Message::create([
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $cabinet->user_id,
            'cabinet_id' => $cabinet->id,
            'objet' => null,
            'contenu' => $contenu,
        ]);

        return back()->with('success', 'Réponse envoyée.');
    }
}
