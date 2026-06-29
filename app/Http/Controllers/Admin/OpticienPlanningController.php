<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCreneauRequest;
use App\Http\Requests\Admin\UpdateCreneauRequest;
use App\Models\CreneauHoraire;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OpticienPlanningController extends Controller
{
    private function authorizeAdmin(User $opticien): void
    {
        $cabinet = auth()->user()->cabinetOptique;
        abort_if(! $cabinet || $opticien->cabinet_id !== $cabinet->id, 403);
    }

    public function index(User $opticien): View
    {
        $this->authorizeAdmin($opticien);

        $creneaux = $opticien->creneaux()
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get();

        $cabinet = auth()->user()->cabinetOptique;

        return view('admin.opticiens.planning', compact('opticien', 'creneaux', 'cabinet'));
    }

    public function store(StoreCreneauRequest $request, User $opticien): RedirectResponse
    {
        $this->authorizeAdmin($opticien);

        $cabinet = auth()->user()->cabinetOptique;
        $jours = $request->jours_semaine;
        $conflits = $this->conflitsJours($opticien->id, $jours, $request->heure_debut, $request->heure_fin);

        if (! empty($conflits)) {
            return back()->withInput()->withErrors([
                'jours_semaine' => 'Un créneau chevauche déjà ces horaires pour : '.implode(', ', $conflits).'.',
            ]);
        }

        $base = [
            'cabinet_id' => $cabinet->id,
            'opticien_id' => $opticien->id,
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

    public function update(UpdateCreneauRequest $request, User $opticien, CreneauHoraire $creneau): RedirectResponse
    {
        $this->authorizeAdmin($opticien);
        abort_if($creneau->opticien_id !== $opticien->id, 403);

        $conflits = $this->conflitsJours(
            $opticien->id,
            [$request->jour_semaine],
            $request->heure_debut,
            $request->heure_fin,
            $creneau->id
        );

        if (! empty($conflits)) {
            return back()->withInput()->withErrors([
                'heure_debut' => 'Un créneau existant chevauche déjà ces horaires pour ce jour.',
            ]);
        }

        $creneau->update($request->validated());

        return back()->with('success', 'Créneau mis à jour.');
    }

    public function destroy(User $opticien, CreneauHoraire $creneau): RedirectResponse
    {
        $this->authorizeAdmin($opticien);
        abort_if($creneau->opticien_id !== $opticien->id, 403);

        $creneau->delete();

        return back()->with('success', 'Créneau supprimé.');
    }

    /**
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
