<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProduitRequest;
use App\Http\Requests\Admin\UpdateProduitRequest;
use App\Models\CabinetOptique;
use App\Models\Produit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProduitController extends Controller
{
    public function index(): View
    {
        $produits = Produit::with('cabinet')->latest()->paginate(15);
        $cabinets = CabinetOptique::where('est_actif', true)->get();

        return view('admin.produits.index', compact('produits', 'cabinets'));
    }

    public function store(StoreProduitRequest $request): RedirectResponse
    {
        Produit::create($request->validated());

        return back()->with('success', 'Produit créé avec succès.');
    }

    public function update(UpdateProduitRequest $request, Produit $produit): RedirectResponse
    {
        $produit->update($request->validated());

        return back()->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit): RedirectResponse
    {
        $produit->delete();

        return back()->with('success', 'Produit supprimé avec succès.');
    }
}
