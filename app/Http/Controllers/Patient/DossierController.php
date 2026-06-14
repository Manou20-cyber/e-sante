<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\UpdateDossierRequest;
use App\Models\DossierMedical;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DossierController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient;

        $dossier = $patient->dossierMedical()->with([
            'ordonnances' => fn ($q) => $q->orderByDesc('date'),
        ])->first();

        $consultations = $patient->consultations()
            ->with('cabinet', 'medecin')
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        return view('patient.dossier.index', compact('patient', 'dossier', 'consultations'));
    }

    public function update(UpdateDossierRequest $request): RedirectResponse
    {
        $patient = auth()->user()->patient;

        DossierMedical::updateOrCreate(
            ['patient_id' => $patient->id],
            $request->validated()
        );

        return back()->with('success', 'Dossier médical mis à jour.');
    }
}
