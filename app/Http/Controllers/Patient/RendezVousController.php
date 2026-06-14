<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreRendezVousRequest;
use App\Http\Requests\Patient\UpdateRendezVousRequest;
use App\Models\RendezVous;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RendezVousController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient;

        $rendezvous = $patient->rendezvous()
            ->with(['cabinet', 'opticien'])
            ->orderByDesc('date')
            ->paginate(10);

        return view('patient.rendezvous.index', compact('rendezvous'));
    }

    public function store(StoreRendezVousRequest $request): RedirectResponse
    {
        $patient = auth()->user()->patient;

        RendezVous::create([
            'patient_id' => $patient->id,
            'cabinet_id' => $request->cabinet_id,
            'date' => $request->date,
            'duree' => 30,
            'type' => $request->type,
            'statut' => 'en_attente',
            'motif' => $request->motif,
        ]);

        return back()->with('success', 'Rendez-vous demandé. Le cabinet vous confirmera bientôt.');
    }

    public function update(UpdateRendezVousRequest $request, RendezVous $rendezvou): RedirectResponse
    {
        abort_if($rendezvou->patient_id !== auth()->user()->patient->id, 403);
        abort_if($rendezvou->statut !== 'en_attente', 422, 'Ce rendez-vous ne peut plus être modifié.');
        abort_if($rendezvou->date->isPast(), 422, 'Ce rendez-vous est passé.');

        $rendezvou->update([
            'date' => $request->date,
            'type' => $request->type,
            'motif' => $request->motif,
        ]);

        return back()->with('success', 'Rendez-vous modifié avec succès.');
    }

    public function destroy(RendezVous $rendezvou): RedirectResponse
    {
        abort_if($rendezvou->patient_id !== auth()->user()->patient->id, 403);
        abort_if(in_array($rendezvou->statut, ['termine', 'annule']), 422, 'Ce rendez-vous ne peut plus être annulé.');

        $rendezvou->update(['statut' => 'annule']);

        return back()->with('success', 'Rendez-vous annulé.');
    }
}
