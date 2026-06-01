<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CabinetOptique;
use App\Models\Commande;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'patients' => Patient::count(),
            'cabinets' => CabinetOptique::count(),
            'rendezvous_aujourd_hui' => RendezVous::whereDate('date', today())->count(),
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
        ];

        $rendezvous_recents = RendezVous::with(['patient.user', 'cabinet'])
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'rendezvous_recents'));
    }
}
