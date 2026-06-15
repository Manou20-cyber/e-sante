<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\View\View;

class FactureController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient;

        $factures = $patient->factures()
            ->with(['cabinet', 'commande', 'consultation', 'paiements'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('patient.factures.index', compact('factures'));
    }

    public function show(Facture $facture): View
    {
        abort_if($facture->patient_id !== auth()->user()->patient->id, 403);

        $facture->load(['cabinet', 'commande.produits', 'consultation', 'paiements']);

        return view('patient.factures.show', compact('facture'));
    }
}
