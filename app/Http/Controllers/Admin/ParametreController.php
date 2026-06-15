<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreParametreRequest;
use App\Http\Requests\Admin\UpdateParametreRequest;
use App\Models\CabinetOptique;
use App\Models\Parametre;
use App\Models\User;
use App\Services\PaletteService;
use App\Services\ParametreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ParametreController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $parametres = Parametre::orderBy('groupe')->orderBy('cle')->paginate(20);
        $palettes = PaletteService::palettes();
        $moncabinet = $user->hasRole('cabinet_admin')
            ? CabinetOptique::where('user_id', $user->id)->first()
            : null;

        return view('admin.parametres.index', compact('parametres', 'palettes', 'moncabinet'));
    }

    public function saveCabinet(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $cabinet = CabinetOptique::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string', 'max:500'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['required', 'string', 'max:10'],
            'telephone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:1024'],
        ]);

        $data = $request->only(['nom', 'adresse', 'ville', 'code_postal', 'telephone', 'email', 'description']);

        if ($request->hasFile('logo')) {
            if ($cabinet->logo) {
                Storage::disk('public')->delete($cabinet->logo);
            }
            $data['logo'] = $request->file('logo')->store('cabinets/logos', 'public');
        } elseif ($request->boolean('supprimer_logo') && $cabinet->logo) {
            Storage::disk('public')->delete($cabinet->logo);
            $data['logo'] = null;
        }

        $cabinet->update($data);

        return redirect()->route('admin.parametres.index')->with('success', 'Informations du cabinet mises à jour.');
    }

    public function saveApparence(Request $request): RedirectResponse
    {
        $request->validate([
            'app_nom' => ['required', 'string', 'max:100'],
            'app_palette' => ['required', 'string', 'in:'.implode(',', array_keys(PaletteService::palettes()))],
            'app_logo' => ['nullable', 'image', 'max:1024'],
        ]);

        Parametre::updateOrCreate(['cle' => 'app.nom'], ['valeur' => $request->app_nom,     'groupe' => 'app', 'est_public' => true]);
        Parametre::updateOrCreate(['cle' => 'app.palette'], ['valeur' => $request->app_palette, 'groupe' => 'app', 'est_public' => true]);

        if ($request->hasFile('app_logo')) {
            $current = Parametre::where('cle', 'app.logo')->value('valeur');
            if ($current) {
                Storage::disk('public')->delete($current);
            }
            $path = $request->file('app_logo')->store('logos', 'public');
            Parametre::updateOrCreate(['cle' => 'app.logo'], ['valeur' => $path, 'groupe' => 'app', 'est_public' => true]);
        } elseif ($request->boolean('supprimer_logo')) {
            $current = Parametre::where('cle', 'app.logo')->value('valeur');
            if ($current) {
                Storage::disk('public')->delete($current);
            }
            Parametre::updateOrCreate(['cle' => 'app.logo'], ['valeur' => null, 'groupe' => 'app', 'est_public' => true]);
        }

        ParametreService::clearCache();

        return redirect()->route('admin.parametres.index')->with('success', 'Apparence mise à jour avec succès.');
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
