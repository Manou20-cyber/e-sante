<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CabinetOptique;
use App\Models\Commande;
use App\Models\Consultation;
use App\Models\ExamenOptique;
use App\Models\Facture;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatistiquesController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $cabinetId = null;

        if (! $user->hasRole('super_admin')) {
            $cabinetId = $user->cabinet_id ?? $user->cabinetOptique?->id;
        }

        $kpis = $this->buildKpis($cabinetId);
        $rdvParMois = $this->rdvParMois($cabinetId);
        $rdvParType = $this->rdvParType($cabinetId);
        $patientsParMois = $this->patientsParMois($cabinetId);
        $caParMois = $this->caParMois($cabinetId);
        $topOpticiens = $this->topOpticiens($cabinetId);

        // Nouvelles données commandes & CA
        $commandesParMois = $this->commandesParMois($cabinetId);
        $caCommandesParMois = $this->caCommandesParMois($cabinetId);
        $commandesParStatut = $this->commandesParStatut($cabinetId);
        $topProduits = $this->topProduits($cabinetId);
        $caRecap = $this->caRecap($cabinetId);

        return view('admin.statistiques.index', compact(
            'kpis',
            'rdvParMois', 'rdvParType', 'patientsParMois', 'caParMois',
            'topOpticiens',
            'commandesParMois', 'caCommandesParMois', 'commandesParStatut',
            'topProduits', 'caRecap'
        ));
    }

    private function buildKpis(?int $cabinetId): array
    {
        $rdvQuery = RendezVous::query();
        $consultQuery = Consultation::query();
        $commandeQuery = Commande::query();
        $factureQuery = Facture::query();

        if ($cabinetId) {
            $rdvQuery->where('cabinet_id', $cabinetId);
            $consultQuery->where('cabinet_id', $cabinetId);
            $commandeQuery->where('cabinet_id', $cabinetId);
            $factureQuery->where('cabinet_id', $cabinetId);
        }

        $caConsultMois = (clone $consultQuery)
            ->whereMonth('date', now()->month)->whereYear('date', now()->year)
            ->sum('montant');

        $caCommandesMois = (clone $factureQuery)
            ->where('statut', 'payee')
            ->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)
            ->sum('montant_ttc');

        $panierMoyen = (clone $commandeQuery)->avg('montant_total') ?? 0;

        return [
            'rdv_total' => (clone $rdvQuery)->count(),
            'rdv_mois' => (clone $rdvQuery)->whereMonth('date', now()->month)->whereYear('date', now()->year)->count(),
            'rdv_aujourd_hui' => (clone $rdvQuery)->whereDate('date', today())->count(),
            'consultations_mois' => (clone $consultQuery)->whereMonth('date', now()->month)->whereYear('date', now()->year)->count(),
            'ca_mois' => $caConsultMois,
            'patients_total' => $cabinetId
                ? Patient::whereHas('rendezvous', fn ($q) => $q->where('cabinet_id', $cabinetId))->count()
                : Patient::count(),
            'examens_total' => $cabinetId
                ? ExamenOptique::whereHas('consultation', fn ($q) => $q->where('cabinet_id', $cabinetId))->count()
                : ExamenOptique::count(),
            'cabinets_actifs' => CabinetOptique::where('est_actif', true)->count(),

            // Commandes & CA
            'commandes_mois' => (clone $commandeQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'commandes_en_attente' => (clone $commandeQuery)->where('statut', 'en_attente')->count(),
            'ca_commandes_mois' => $caCommandesMois,
            'ca_total_mois' => $caConsultMois + $caCommandesMois,
            'panier_moyen' => (int) $panierMoyen,
            'factures_payees' => (clone $factureQuery)->where('statut', 'payee')->count(),
            'factures_en_attente' => (clone $factureQuery)->where('statut', 'emise')->count(),
        ];
    }

    private function rdvParMois(?int $cabinetId): array
    {
        $query = RendezVous::select(
            DB::raw('YEAR(date) as annee'),
            DB::raw('MONTH(date) as mois'),
            DB::raw('COUNT(*) as total')
        )
            ->where('date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois');

        if ($cabinetId) {
            $query->where('cabinet_id', $cabinetId);
        }

        $rows = $query->get()->keyBy(fn ($r) => "{$r->annee}-{$r->mois}");

        return $this->buildMonthSeries($rows, 'total');
    }

    private function rdvParType(?int $cabinetId): array
    {
        $query = RendezVous::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type');

        if ($cabinetId) {
            $query->where('cabinet_id', $cabinetId);
        }

        $rows = $query->get();

        return [
            'labels' => $rows->pluck('type')->toArray(),
            'data' => $rows->pluck('total')->toArray(),
        ];
    }

    private function patientsParMois(?int $cabinetId): array
    {
        $query = Patient::select(
            DB::raw('YEAR(created_at) as annee'),
            DB::raw('MONTH(created_at) as mois'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois');

        if ($cabinetId) {
            $query->whereHas('rendezvous', fn ($q) => $q->where('cabinet_id', $cabinetId));
        }

        $rows = $query->get()->keyBy(fn ($r) => "{$r->annee}-{$r->mois}");

        return $this->buildMonthSeries($rows, 'total');
    }

    private function caParMois(?int $cabinetId): array
    {
        $query = Consultation::select(
            DB::raw('YEAR(date) as annee'),
            DB::raw('MONTH(date) as mois'),
            DB::raw('SUM(montant) as total')
        )
            ->where('date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois');

        if ($cabinetId) {
            $query->where('cabinet_id', $cabinetId);
        }

        $rows = $query->get()->keyBy(fn ($r) => "{$r->annee}-{$r->mois}");

        return $this->buildMonthSeries($rows, 'total', true);
    }

    private function commandesParMois(?int $cabinetId): array
    {
        $query = Commande::select(
            DB::raw('YEAR(created_at) as annee'),
            DB::raw('MONTH(created_at) as mois'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois');

        if ($cabinetId) {
            $query->where('cabinet_id', $cabinetId);
        }

        $rows = $query->get()->keyBy(fn ($r) => "{$r->annee}-{$r->mois}");

        return $this->buildMonthSeries($rows, 'total');
    }

    private function caCommandesParMois(?int $cabinetId): array
    {
        $query = Facture::select(
            DB::raw('YEAR(updated_at) as annee'),
            DB::raw('MONTH(updated_at) as mois'),
            DB::raw('SUM(montant_ttc) as total')
        )
            ->where('statut', 'payee')
            ->where('updated_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois');

        if ($cabinetId) {
            $query->where('cabinet_id', $cabinetId);
        }

        $rows = $query->get()->keyBy(fn ($r) => "{$r->annee}-{$r->mois}");

        return $this->buildMonthSeries($rows, 'total', true);
    }

    private function commandesParStatut(?int $cabinetId): array
    {
        $query = Commande::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut');

        if ($cabinetId) {
            $query->where('cabinet_id', $cabinetId);
        }

        $rows = $query->get();

        $labels = ['en_attente' => 'En attente', 'confirmee' => 'Confirmée', 'en_preparation' => 'En préparation', 'prete' => 'Prête', 'livree' => 'Livrée', 'annulee' => 'Annulée'];
        $colors = ['en_attente' => '#f59e0b', 'confirmee' => '#3b82f6', 'en_preparation' => '#8b5cf6', 'prete' => '#14b8a6', 'livree' => '#22c55e', 'annulee' => '#ef4444'];

        return [
            'labels' => $rows->map(fn ($r) => $labels[$r->statut] ?? $r->statut)->toArray(),
            'data' => $rows->pluck('total')->toArray(),
            'colors' => $rows->map(fn ($r) => $colors[$r->statut] ?? '#94a3b8')->toArray(),
        ];
    }

    private function topProduits(?int $cabinetId): Collection
    {
        $query = DB::table('commande_produit')
            ->join('produits', 'commande_produit.produit_id', '=', 'produits.id')
            ->join('commandes', 'commande_produit.commande_id', '=', 'commandes.id')
            ->select(
                'produits.id',
                'produits.libelle',
                'produits.categorie',
                DB::raw('SUM(commande_produit.quantite) as quantite_totale'),
                DB::raw('SUM(commande_produit.quantite * commande_produit.prix_unitaire) as ca_total')
            )
            ->groupBy('produits.id', 'produits.libelle', 'produits.categorie')
            ->orderByDesc('quantite_totale')
            ->limit(5);

        if ($cabinetId) {
            $query->where('commandes.cabinet_id', $cabinetId);
        }

        return $query->get();
    }

    /**
     * Récapitulatif CA : consultations / commandes / total — mois et année.
     */
    private function caRecap(?int $cabinetId): array
    {
        $consultQuery = Consultation::query();
        $factureQuery = Facture::where('statut', 'payee');

        if ($cabinetId) {
            $consultQuery->where('cabinet_id', $cabinetId);
            $factureQuery->where('cabinet_id', $cabinetId);
        }

        $caConsultMois = (clone $consultQuery)->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('montant');
        $caConsultAnnee = (clone $consultQuery)->whereYear('date', now()->year)->sum('montant');

        $caCommandesMois = (clone $factureQuery)->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->sum('montant_ttc');
        $caCommandesAnnee = (clone $factureQuery)->whereYear('updated_at', now()->year)->sum('montant_ttc');

        return [
            'ca_consult_mois' => (int) $caConsultMois,
            'ca_consult_annee' => (int) $caConsultAnnee,
            'ca_commandes_mois' => (int) $caCommandesMois,
            'ca_commandes_annee' => (int) $caCommandesAnnee,
            'ca_total_mois' => (int) ($caConsultMois + $caCommandesMois),
            'ca_total_annee' => (int) ($caConsultAnnee + $caCommandesAnnee),
        ];
    }

    private function topOpticiens(?int $cabinetId): Collection
    {
        $query = RendezVous::select('opticien_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('opticien_id')
            ->groupBy('opticien_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('opticien');

        if ($cabinetId) {
            $query->where('cabinet_id', $cabinetId);
        }

        return $query->get();
    }

    /**
     * Construit une série sur les 12 derniers mois à partir d'une collection keyed par "année-mois".
     */
    private function buildMonthSeries(mixed $rows, string $field, bool $asInt = false): array
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $key = "{$d->year}-{$d->month}";
            $labels[] = $d->translatedFormat('M Y');
            $val = $rows[$key]->$field ?? 0;
            $data[] = $asInt ? (int) $val : $val;
        }

        return compact('labels', 'data');
    }
}
