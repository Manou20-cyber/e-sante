<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCreneauRequest;
use App\Http\Requests\Admin\UpdateCreneauRequest;
use App\Models\CreneauHoraire;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlanningController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $creneaux = $user->creneaux()->with('cabinet')->orderBy('jour_semaine')->orderBy('heure_debut')->get();

        $cabinet = $user->hasRole('cabinet_admin')
            ? $user->cabinetOptique
            : $user->cabinet;

        return view('admin.planning.index', compact('creneaux', 'cabinet'));
    }

    public function store(StoreCreneauRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $cabinet = $user->hasRole('cabinet_admin') ? $user->cabinetOptique : $user->cabinet;

        abort_if(! $cabinet, 403, 'Aucun cabinet associé.');

        $base = [
            'cabinet_id' => $cabinet->id,
            'opticien_id' => $user->id,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'duree_consultation' => $request->duree_consultation,
            'capacite_max' => $request->capacite_max ?? 1,
            'prix' => $request->prix,
            'est_actif' => true,
        ];

        $jours = $request->jours_semaine;
        foreach ($jours as $jour) {
            CreneauHoraire::create(array_merge($base, ['jour_semaine' => (int) $jour]));
        }

        $nb = count($jours);

        return back()->with('success', $nb === 1 ? 'Créneau ajouté.' : "{$nb} créneaux ajoutés.");
    }

    public function update(UpdateCreneauRequest $request, CreneauHoraire $creneau): RedirectResponse
    {
        abort_if($creneau->opticien_id !== auth()->id(), 403);

        $creneau->update($request->validated());

        return back()->with('success', 'Créneau mis à jour.');
    }

    public function destroy(CreneauHoraire $creneau): RedirectResponse
    {
        abort_if($creneau->opticien_id !== auth()->id(), 403);

        $creneau->delete();

        return back()->with('success', 'Créneau supprimé.');
    }
}
