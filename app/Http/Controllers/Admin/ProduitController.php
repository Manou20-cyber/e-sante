<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProduitRequest;
use App\Http\Requests\Admin\UpdateProduitRequest;
use App\Models\CabinetOptique;
use App\Models\Produit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProduitController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $query = Produit::with('cabinet')->latest();

        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
            $query->where('cabinet_id', $cabinetId);
        }

        $produits = $query->paginate(15);
        $cabinets = CabinetOptique::where('est_actif', true)->get();

        return view('admin.produits.index', compact('produits', 'cabinets'));
    }

    public function store(StoreProduitRequest $request): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne crée pas de produits.');

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['images'] = [$request->file('image')->store('produits/images', 'public')];
        }

        Produit::create($data);

        return back()->with('success', 'Produit créé avec succès.');
    }

    public function update(UpdateProduitRequest $request, Produit $produit): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne modifie pas les produits.');

        $data = $request->validated();

        if ($request->hasFile('image')) {
            foreach ($produit->images ?? [] as $old) {
                Storage::disk('public')->delete($old);
            }
            $data['images'] = [$request->file('image')->store('produits/images', 'public')];
        }

        $produit->update($data);

        return back()->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne supprime pas les produits.');

        foreach ($produit->images ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }

        $produit->delete();

        return back()->with('success', 'Produit supprimé avec succès.');
    }
}
