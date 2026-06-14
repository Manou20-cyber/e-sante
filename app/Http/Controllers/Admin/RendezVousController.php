<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRendezVousRequest;
use App\Http\Requests\Admin\UpdateRendezVousRequest;
use App\Models\CabinetOptique;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Notifications\RdvStatutChange;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RendezVousController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $query = RendezVous::with(['patient.user', 'cabinet'])->latest('date');

        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
            $query->where('cabinet_id', $cabinetId);
        }

        $rendezvous = $query->paginate(15);

        $patients = Patient::with('user')->get();
        $cabinets = CabinetOptique::where('est_actif', true)->get();

        return view('admin.rendezvous.index', compact('rendezvous', 'patients', 'cabinets'));
    }

    public function store(StoreRendezVousRequest $request): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne crée pas de rendez-vous.');

        RendezVous::create($request->validated());

        return back()->with('success', 'Rendez-vous créé avec succès.');
    }

    public function update(UpdateRendezVousRequest $request, RendezVous $rendezvou): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne modifie pas les rendez-vous.');

        $ancienStatut = $rendezvou->statut;
        $rendezvou->update($request->validated());

        if ($rendezvou->statut !== $ancienStatut && $rendezvou->patient?->user) {
            $rendezvou->patient->user->notify(new RdvStatutChange($rendezvou));
        }

        return back()->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    public function destroy(RendezVous $rendezvou): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne supprime pas les rendez-vous.');

        $rendezvou->delete();

        return back()->with('success', 'Rendez-vous supprimé avec succès.');
    }
}
