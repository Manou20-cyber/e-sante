<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    protected $fillable = [
        'patient_id',
        'cabinet_id',
        'commande_id',
        'consultation_id',
        'numero',
        'montant_ht',
        'taux_tva',
        'montant_ttc',
        'statut',
        'date_emission',
        'date_echeance',
        'chemin_pdf',
    ];

    protected function casts(): array
    {
        return [
            'montant_ht' => 'decimal:2',
            'taux_tva' => 'decimal:2',
            'montant_ttc' => 'decimal:2',
            'date_emission' => 'date',
            'date_echeance' => 'date',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(CabinetOptique::class, 'cabinet_id');
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
