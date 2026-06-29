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

        $jours = $request->jours_semaine;
        $conflits = $this->conflitsJours($user->id, $jours, $request->heure_debut, $request->heure_fin);

        if (! empty($conflits)) {
            return back()->withInput()->withErrors([
                'jours_semaine' => 'Un créneau existe déjà pour les horaires suivants : '.implode(', ', $conflits).'.',
            ]);
        }

        $base = [
            'cabinet_id' => $cabinet->id,
            'opticien_id' => $user->id,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'duree_consultation' => $request->duree_consultation,
            'capacite_max' => $request->capacite_max ?? 1,
            'prix' => $request->prix,
            'accepte_video' => $request->boolean('accepte_video'),
            'est_actif' => true,
        ];

        foreach ($jours as $jour) {
            CreneauHoraire::create(array_merge($base, ['jour_semaine' => (int) $jour]));
        }

        $nb = count($jours);

        return back()->with('success', $nb === 1 ? 'Créneau ajouté.' : "{$nb} créneaux ajoutés.");
    }

    public function update(UpdateCreneauRequest $request, CreneauHoraire $creneau): RedirectResponse
    {
        $user = auth()->user();
        abort_if($creneau->opticien_id !== $user->id, 403);

        $conflits = $this->conflitsJours(
            $user->id,
            [$request->jour_semaine],
            $request->heure_debut,
            $request->heure_fin,
            $creneau->id
        );

        if (! empty($conflits)) {
            return back()->withInput()->withErrors([
                'heure_debut' => 'Un créneau existe déjà pour ces horaires pour ce jour.',
            ]);
        }

        $creneau->update($request->validated());

        return back()->with('success', 'Créneau mis à jour.');
    }

    public function destroy(CreneauHoraire $creneau): RedirectResponse
    {
        abort_if($creneau->opticien_id !== auth()->user()->id, 403);

        $creneau->delete();

        return back()->with('success', 'Créneau supprimé.');
    }

    /**
     * Retourne les noms de jours en conflit (chevauchement horaire pour le même opticien).
     *
     * @param  int[]  $jours
     * @return string[]
     */
    private function conflitsJours(int $opticienId, array $jours, string $heureDebut, string $heureFin, ?int $excludeId = null): array
    {
        $labels = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
        $conflits = [];

        foreach ($jours as $jour) {
            $query = CreneauHoraire::where('opticien_id', $opticienId)
                ->where('jour_semaine', (int) $jour)
                ->where('heure_debut', '<', $heureFin)
                ->where('heure_fin', '>', $heureDebut);

            if ($excludeId !== null) {
                $query->where('id', '!=', $excludeId);
            }

            if ($query->exists()) {
                $conflits[] = $labels[(int) $jour] ?? "Jour {$jour}";
            }
        }

        return $conflits;
    }
}
