<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExamenRequest;
use App\Http\Requests\Admin\UpdateExamenRequest;
use App\Models\Consultation;
use App\Models\ExamenOptique;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExamenController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $query = ExamenOptique::with(['patient.user', 'consultation.cabinet', 'consultation.medecin'])
            ->orderByDesc('created_at');

        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
            $query->whereHas('consultation', fn ($q) => $q->where('cabinet_id', $cabinetId));
        }

        $examens = $query->paginate(15);

        $patients = $this->getPatientsForCabinet($user);
        $consultations = $this->getConsultationsForCabinet($user);

        return view('admin.examens.index', compact('examens', 'patients', 'consultations'));
    }

    public function store(StoreExamenRequest $request): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne crée pas d\'examens.');

        ExamenOptique::create($request->validated());

        return back()->with('success', 'Examen enregistré avec succès.');
    }

    public function update(UpdateExamenRequest $request, ExamenOptique $examen): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne modifie pas les examens.');

        $examen->update($request->validated());

        return back()->with('success', 'Examen mis à jour avec succès.');
    }

    public function destroy(ExamenOptique $examen): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne supprime pas les examens.');

        $examen->delete();

        return back()->with('success', 'Examen supprimé.');
    }

    private function getPatientsForCabinet($user)
    {
        $query = Patient::with('user');
        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
            $query->whereHas('rendezvous', fn ($q) => $q->where('cabinet_id', $cabinetId));
        }

        return $query->get();
    }

    private function getConsultationsForCabinet($user)
    {
        $query = Consultation::with(['patient.user'])->whereDoesntHave('examen');
        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
            $query->where('cabinet_id', $cabinetId);
        }

        return $query->latest('date')->get();
    }
}
