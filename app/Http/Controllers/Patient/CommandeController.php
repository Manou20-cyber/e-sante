<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreCommandeRequest;
use App\Http\Requests\Patient\StoreRetourRequest;
use App\Models\CabinetOptique;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Produit;
use App\Models\RetourCommande;
use App\Notifications\NouvelleCommande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient;

        $commandes = $patient->commandes()
            ->with(['cabinet', 'produits', 'retour', 'facture'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('patient.commandes.index', compact('commandes'));
    }

    public function create(): View
    {
        $patient = auth()->user()->patient;

        $cabinets = CabinetOptique::where('est_actif', true)
            ->whereHas('rendezvous', fn ($q) => $q->where('patient_id', $patient->id))
            ->with(['produits' => fn ($q) => $q->where('est_actif', true)->select('id', 'cabinet_id', 'libelle', 'reference', 'prix', 'categorie', 'marque', 'stock')])
            ->get(['id', 'nom', 'adresse', 'ville']);

        $ordonnances = $patient->dossierMedical?->ordonnances()->orderByDesc('date')->get() ?? collect();

        return view('patient.commandes.create', compact('cabinets', 'ordonnances'));
    }

    public function store(StoreCommandeRequest $request): RedirectResponse
    {
        $patient = auth()->user()->patient;
        $commande = null;

        DB::transaction(function () use ($request, $patient, &$commande) {
            $commande = Commande::create([
                'patient_id' => $patient->id,
                'cabinet_id' => $request->cabinet_id,
                'ordonnance_id' => $request->ordonnance_id,
                'numero' => 'CMD-'.strtoupper(Str::random(8)),
                'adresse_livraison' => $request->adresse_livraison,
                'notes' => $request->notes,
                'statut' => 'en_attente',
                'montant_total' => 0,
            ]);

            $total = 0;
            foreach ($request->produits as $item) {
                $produit = Produit::findOrFail($item['id']);
                $commande->produits()->attach($produit->id, [
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $produit->prix,
                ]);
                $total += $produit->prix * $item['quantite'];
            }

            $commande->update(['montant_total' => $total]);

            Facture::create([
                'patient_id' => $patient->id,
                'cabinet_id' => $commande->cabinet_id,
                'commande_id' => $commande->id,
                'numero' => 'FAC-'.strtoupper(Str::random(8)),
                'montant_ht' => $total,
                'taux_tva' => 0,
                'montant_ttc' => $total,
                'statut' => 'emise',
                'date_emission' => now(),
                'date_echeance' => now()->addDays(30),
            ]);
        });

        $commande->load(['patient.user', 'cabinet', 'facture']);
        $commande->cabinet->admin?->notify(new NouvelleCommande($commande));

        return redirect()->route('patient.factures.show', $commande->facture)
            ->with('success', 'Commande '.$commande->numero.' créée ! Réglez votre facture pour confirmer.');
    }

    public function retour(StoreRetourRequest $request, Commande $commande): RedirectResponse
    {
        abort_if($commande->patient_id !== auth()->user()->patient->id, 403);
        abort_if($commande->statut !== 'livree', 422, 'Seules les commandes livrées peuvent être retournées.');
        abort_if($commande->retour()->exists(), 422, 'Un retour est déjà en cours pour cette commande.');

        RetourCommande::create([
            'commande_id' => $commande->id,
            'patient_id' => $commande->patient_id,
            'raison' => $request->raison,
            'statut' => 'en_attente',
        ]);

        return back()->with('success', 'Demande de retour envoyée. Le cabinet vous contactera.');
    }
}
