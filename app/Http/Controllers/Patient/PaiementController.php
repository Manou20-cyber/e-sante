<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Paiement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    public function store(Request $request, Facture $facture): RedirectResponse
    {
        abort_if($facture->patient_id !== auth()->user()->patient->id, 403);
        abort_if($facture->statut === 'payee', 422, 'Cette facture est déjà réglée.');
        abort_if($facture->statut === 'annulee', 422, 'Cette facture est annulée.');

        $request->validate([
            'operateur' => ['required', 'in:mtn,orange'],
            'telephone' => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
        ]);

        Paiement::create([
            'facture_id' => $facture->id,
            'montant' => $facture->montant_ttc,
            'methode' => 'mobile_money',
            'reference' => strtoupper($request->operateur).'-'.strtoupper(Str::random(10)),
            'date_paiement' => now(),
            'notes' => 'Paiement '.($request->operateur === 'mtn' ? 'MTN Mobile Money' : 'Orange Money').' via '.$request->telephone,
        ]);

        $facture->update(['statut' => 'payee']);

        return redirect()->route('patient.factures.index')
            ->with('success', 'Paiement effectué avec succès. Merci !');
    }
}
