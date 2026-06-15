<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CabinetOptique;
use App\Models\RetourCommande;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RetourController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = auth()->user();

        $baseQuery = RetourCommande::query();

        if ($user->hasRole('cabinet_admin')) {
            $cabinetId = CabinetOptique::where('user_id', $user->id)->value('id') ?? 0;
            $baseQuery->whereHas('commande', fn ($q) => $q->where('cabinet_id', $cabinetId));
        } elseif ($user->hasRole('opticien')) {
            $baseQuery->whereHas('commande', fn ($q) => $q->where('cabinet_id', $user->cabinet_id));
        }

        $stats = [
            'en_attente' => (clone $baseQuery)->where('statut', 'en_attente')->count(),
            'approuve' => (clone $baseQuery)->where('statut', 'approuve')->count(),
            'refuse' => (clone $baseQuery)->where('statut', 'refuse')->count(),
            'traite' => (clone $baseQuery)->where('statut', 'traite')->count(),
        ];

        if ($request->filled('statut')) {
            $baseQuery->where('statut', $request->statut);
        }

        $retours = $baseQuery
            ->with(['commande.cabinet', 'commande.produits', 'patient.user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.retours.index', compact('retours', 'stats'));
    }

    public function update(Request $request, RetourCommande $retour): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->hasRole('cabinet_admin')
                ? CabinetOptique::where('user_id', $user->id)->value('id')
                : $user->cabinet_id;

            abort_if($retour->commande->cabinet_id !== $cabinetId, 403);
        }

        $request->validate([
            'statut' => ['required', 'in:en_attente,approuve,refuse,traite'],
            'notes_cabinet' => ['nullable', 'string', 'max:2000'],
            'montant_rembourse' => ['nullable', 'numeric', 'min:0'],
        ]);

        $retour->update($request->only(['statut', 'notes_cabinet', 'montant_rembourse']));

        return back()->with('success', 'Retour mis à jour avec succès.');
    }
}
