<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\CabinetOptique;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $patient = auth()->user()->patient;

        $prochainRdv = $patient?->rendezvous()
            ->where('statut', '!=', 'annule')
            ->where('date', '>=', now())
            ->orderBy('date')
            ->with('cabinet')
            ->first();

        $messagesNonLus = auth()->user()->messagesRecus()->whereNull('lu_at')->count();

        $commandesActives = $patient?->commandes()
            ->whereNotIn('statut', ['livree', 'annulee'])
            ->count() ?? 0;

        $derniersRdv = $patient?->rendezvous()
            ->with('cabinet')
            ->orderByDesc('date')
            ->limit(3)
            ->get();

        $cabinets = CabinetOptique::where('est_actif', true)
            ->withCount('opticiens')
            ->with(['opticiens.creneaux' => fn ($q) => $q->where('est_actif', true)])
            ->get();

        return view('patient.dashboard', compact(
            'patient',
            'prochainRdv',
            'messagesNonLus',
            'commandesActives',
            'derniersRdv',
            'cabinets',
        ));
    }
}
