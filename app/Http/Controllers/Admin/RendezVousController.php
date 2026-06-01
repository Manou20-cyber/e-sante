<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRendezVousRequest;
use App\Http\Requests\Admin\UpdateRendezVousRequest;
use App\Models\CabinetOptique;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RendezVousController extends Controller
{
    public function index(): View
    {
        $rendezvous = RendezVous::with(['patient.user', 'cabinet'])
            ->latest('date')
            ->paginate(15);

        $patients = Patient::with('user')->get();
        $cabinets = CabinetOptique::where('est_actif', true)->get();

        return view('admin.rendezvous.index', compact('rendezvous', 'patients', 'cabinets'));
    }

    public function store(StoreRendezVousRequest $request): RedirectResponse
    {
        RendezVous::create($request->validated());

        return back()->with('success', 'Rendez-vous créé avec succès.');
    }

    public function update(UpdateRendezVousRequest $request, RendezVous $rendezvou): RedirectResponse
    {
        $rendezvou->update($request->validated());

        return back()->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    public function destroy(RendezVous $rendezvou): RedirectResponse
    {
        $rendezvou->delete();

        return back()->with('success', 'Rendez-vous supprimé avec succès.');
    }
}
