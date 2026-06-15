<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrdonnanceRequest;
use App\Models\DossierMedical;
use App\Models\Ordonnance;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DossierCabinetController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $query = Patient::with(['user', 'dossierMedical'])
            ->withCount(['rendezvous', 'consultations']);

        if ($user->hasRole('super_admin')) {
            $patients = $query->paginate(20);
        } else {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
            $patients = $query
                ->whereHas('rendezvous', fn ($q) => $q->where('cabinet_id', $cabinetId))
                ->paginate(20);
        }

        return view('admin.dossiers.index', compact('patients'));
    }

    public function show(Patient $patient): View
    {
        $patient->load([
            'user',
            'dossierMedical.ordonnances' => fn ($q) => $q->orderByDesc('date'),
            'dossierMedical.documents' => fn ($q) => $q->orderByDesc('created_at'),
            'consultations' => fn ($q) => $q->with(['cabinet', 'medecin', 'examen'])->orderByDesc('date'),
            'examens' => fn ($q) => $q->with('consultation')->orderByDesc('created_at'),
        ]);

        return view('admin.dossiers.show', compact('patient'));
    }

    public function storeOrdonnance(StoreOrdonnanceRequest $request, Patient $patient): RedirectResponse
    {
        abort_if(auth()->user()->hasRole('super_admin'), 403, 'Le super admin ne peut pas ajouter d\'ordonnances.');

        $dossier = DossierMedical::firstOrCreate(['patient_id' => $patient->id]);

        Ordonnance::create(array_merge(
            $request->validated(),
            ['dossier_id' => $dossier->id]
        ));

        return back()->with('success', 'Ordonnance ajoutée au dossier.');
    }
}
