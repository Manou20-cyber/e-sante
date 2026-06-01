<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreParametreRequest;
use App\Http\Requests\Admin\UpdateParametreRequest;
use App\Models\Parametre;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParametreController extends Controller
{
    public function index(): View
    {
        $parametres = Parametre::orderBy('groupe')->orderBy('cle')->paginate(20);
        $groupes = Parametre::distinct()->pluck('groupe');

        return view('admin.parametres.index', compact('parametres', 'groupes'));
    }

    public function store(StoreParametreRequest $request): RedirectResponse
    {
        Parametre::create($request->validated());

        return back()->with('success', 'Paramètre créé avec succès.');
    }

    public function update(UpdateParametreRequest $request, Parametre $parametre): RedirectResponse
    {
        $parametre->update($request->validated());

        return back()->with('success', 'Paramètre mis à jour avec succès.');
    }

    public function destroy(Parametre $parametre): RedirectResponse
    {
        $parametre->delete();

        return back()->with('success', 'Paramètre supprimé avec succès.');
    }
}
