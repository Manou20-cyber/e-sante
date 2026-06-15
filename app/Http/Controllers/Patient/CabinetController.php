<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreRendezVousRequest;
use App\Models\CabinetOptique;
use App\Models\CreneauHoraire;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CabinetController extends Controller
{
    public function index(): View
    {
        $cabinets = CabinetOptique::where('est_actif', true)
            ->withCount('opticiens')
            ->with(['opticiens.creneaux' => fn ($q) => $q->where('est_actif', true)])
            ->get();

        return view('patient.cabinets.index', compact('cabinets'));
    }

    public function show(CabinetOptique $cabinet): View
    {
        abort_if(! $cabinet->est_actif, 404);

        $cabinet->load([
            'opticiens' => fn ($q) => $q->with(['creneaux' => fn ($q) => $q->where('est_actif', true)->orderBy('jour_semaine')->orderBy('heure_debut')]),
        ]);

        return view('patient.cabinets.show', compact('cabinet'));
    }

    public function opticien(CabinetOptique $cabinet, User $opticien): View
    {
        abort_if($opticien->cabinet_id !== $cabinet->id, 404);

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        $creneaux = $opticien->creneaux()
            ->where('est_actif', true)
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy('jour_semaine');

        return view('patient.cabinets.opticien', compact('cabinet', 'opticien', 'creneaux', 'jours'));
    }

    public function book(StoreRendezVousRequest $request, CabinetOptique $cabinet, User $opticien): RedirectResponse
    {
        abort_if($opticien->cabinet_id !== $cabinet->id, 403);

        $patient = auth()->user()->patient;
        $creneau = CreneauHoraire::findOrFail($request->creneau_id);

        $base = [
            'patient_id' => $patient->id,
            'cabinet_id' => $cabinet->id,
            'opticien_id' => $opticien->id,
            'creneau_id' => $creneau->id,
            'duree' => $creneau->duree_consultation,
            'type' => $request->type,
            'statut' => 'en_attente',
            'motif' => $request->motif,
        ];

        foreach ($request->heures as $heure) {
            RendezVous::create(array_merge($base, [
                'date' => $request->date.' '.$heure,
            ]));
        }

        $nb = count($request->heures);
        $message = $nb === 1
            ? 'Rendez-vous demandé avec '.$opticien->name.'. Le cabinet vous confirmera bientôt.'
            : "{$nb} rendez-vous demandés avec {$opticien->name}. Le cabinet vous confirmera bientôt.";

        return redirect()->route('patient.rendezvous.index')->with('success', $message);
    }
}
