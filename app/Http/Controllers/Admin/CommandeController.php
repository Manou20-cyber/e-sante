<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommandeRequest;
use App\Http\Requests\Admin\UpdateCommandeRequest;
use App\Models\CabinetOptique;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Patient;
use App\Models\Produit;
use App\Notifications\CommandeStatutChange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(): View
    {
        $commandes = Commande::with(['patient.user', 'cabinet', 'produits', 'facture'])
            ->latest()
            ->paginate(15);

        $patients = Patient::with('user')->get();
        $cabinets = CabinetOptique::where('est_actif', true)->get();
        $produits = Produit::where('est_actif', true)->get();

        return view('admin.commandes.index', compact('commandes', 'patients', 'cabinets', 'produits'));
    }

    public function store(StoreCommandeRequest $request): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne crée pas de commandes.');

        DB::transaction(function () use ($request) {
            $commande = Commande::create([
                'patient_id' => $request->patient_id,
                'cabinet_id' => $request->cabinet_id,
                'ordonnance_id' => $request->ordonnance_id,
                'numero' => 'CMD-'.strtoupper(Str::random(8)),
                'adresse_livraison' => $request->adresse_livraison,
                'notes' => $request->notes,
                'statut' => 'en_attente',
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
                'patient_id' => $commande->patient_id,
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

        return back()->with('success', 'Commande créée avec succès.');
    }

    public function update(UpdateCommandeRequest $request, Commande $commande): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne modifie pas les commandes.');

        $ancienStatut = $commande->statut;
        $commande->update($request->validated());

        if ($commande->statut !== $ancienStatut && $commande->patient?->user) {
            $commande->patient->user->notify(new CommandeStatutChange($commande));
        }

        return back()->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne supprime pas les commandes.');

        $commande->delete();

        return back()->with('success', 'Commande supprimée avec succès.');
    }
}
