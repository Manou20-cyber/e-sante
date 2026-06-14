<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConsultationRequest;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Models\CabinetOptique;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $query = Consultation::with(['patient.user', 'cabinet', 'medecin'])->latest('date');

        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
            $query->where('cabinet_id', $cabinetId);
        }

        $consultations = $query->paginate(15);

        $patients = Patient::with('user')->get();
        $cabinets = CabinetOptique::where('est_actif', true)->get();
        $medecins = User::role('opticien')->orWhereHas('roles', fn ($q) => $q->where('name', 'cabinet_admin'))->get();

        return view('admin.consultations.index', compact('consultations', 'patients', 'cabinets', 'medecins'));
    }

    public function store(StoreConsultationRequest $request): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne crée pas de consultations.');

        Consultation::create($request->validated());

        return back()->with('success', 'Consultation créée avec succès.');
    }

    public function update(UpdateConsultationRequest $request, Consultation $consultation): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne modifie pas les consultations.');

        $consultation->update($request->validated());

        return back()->with('success', 'Consultation mise à jour avec succès.');
    }

    public function destroy(Consultation $consultation): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne supprime pas les consultations.');

        $consultation->delete();

        return back()->with('success', 'Consultation supprimée avec succès.');
    }
}
