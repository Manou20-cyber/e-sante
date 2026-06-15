<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoriqueController extends Controller
{
    public function __invoke(Request $request): View
    {
        $patient = auth()->user()->patient;

        $rendezvous = $patient->rendezvous()->with('cabinet')->orderByDesc('date')->get()
            ->map(fn ($rdv) => [
                'type' => 'rendezvous',
                'date' => $rdv->date,
                'titre' => "Rendez-vous — {$rdv->type}",
                'detail' => $rdv->cabinet->nom,
                'statut' => $rdv->statut,
                'couleur' => 'blue',
            ]);

        $commandes = $patient->commandes()->orderByDesc('created_at')->get()
            ->map(fn ($cmd) => [
                'type' => 'commande',
                'date' => $cmd->created_at,
                'titre' => "Commande {$cmd->numero}",
                'detail' => number_format($cmd->montant_total, 0, ',', ' ').' XAF',
                'statut' => $cmd->statut,
                'couleur' => 'purple',
            ]);

        $documents = $patient->documents()->orderByDesc('created_at')->get()
            ->map(fn ($doc) => [
                'type' => 'document',
                'date' => $doc->created_at,
                'titre' => "Document — {$doc->nom}",
                'detail' => ucfirst($doc->type),
                'statut' => null,
                'couleur' => 'green',
            ]);

        $timeline = $rendezvous->concat($commandes)->concat($documents)
            ->sortByDesc('date')
            ->values();

        return view('patient.historique.index', compact('timeline'));
    }
}
