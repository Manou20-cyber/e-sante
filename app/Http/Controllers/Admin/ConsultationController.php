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
        $consultations = Consultation::with(['patient.user', 'cabinet', 'medecin'])
            ->latest('date')
            ->paginate(15);

        $patients = Patient::with('user')->get();
        $cabinets = CabinetOptique::where('est_actif', true)->get();
        $medecins = User::role('opticien')->orWhereHas('roles', fn ($q) => $q->where('name', 'cabinet_admin'))->get();

        return view('admin.consultations.index', compact('consultations', 'patients', 'cabinets', 'medecins'));
    }

    public function store(StoreConsultationRequest $request): RedirectResponse
    {
        Consultation::create($request->validated());

        return back()->with('success', 'Consultation créée avec succès.');
    }

    public function update(UpdateConsultationRequest $request, Consultation $consultation): RedirectResponse
    {
        $consultation->update($request->validated());

        return back()->with('success', 'Consultation mise à jour avec succès.');
    }

    public function destroy(Consultation $consultation): RedirectResponse
    {
        $consultation->delete();

        return back()->with('success', 'Consultation supprimée avec succès.');
    }
}
