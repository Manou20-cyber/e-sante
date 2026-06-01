<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommandeRequest;
use App\Http\Requests\Admin\UpdateCommandeRequest;
use App\Models\CabinetOptique;
use App\Models\Commande;
use App\Models\Patient;
use App\Models\Produit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(): View
    {
        $commandes = Commande::with(['patient.user', 'cabinet', 'produits'])
            ->latest()
            ->paginate(15);

        $patients = Patient::with('user')->get();
        $cabinets = CabinetOptique::where('est_actif', true)->get();
        $produits = Produit::where('est_actif', true)->get();

        return view('admin.commandes.index', compact('commandes', 'patients', 'cabinets', 'produits'));
    }

    public function store(StoreCommandeRequest $request): RedirectResponse
    {
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
        });

        return back()->with('success', 'Commande créée avec succès.');
    }

    public function update(UpdateCommandeRequest $request, Commande $commande): RedirectResponse
    {
        $commande->update($request->validated());

        return back()->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        $commande->delete();

        return back()->with('success', 'Commande supprimée avec succès.');
    }
}
