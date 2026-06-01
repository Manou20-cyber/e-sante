<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ordonnance extends Model
{
    protected $fillable = [
        'dossier_id',
        'consultation_id',
        'date',
        'sphere_od',
        'sphere_og',
        'cylindre_od',
        'cylindre_og',
        'axe_od',
        'axe_og',
        'addition_od',
        'addition_og',
        'ecart_pupillaire',
        'notes',
        'chemin_pdf',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'sphere_od' => 'decimal:2',
            'sphere_og' => 'decimal:2',
            'cylindre_od' => 'decimal:2',
            'cylindre_og' => 'decimal:2',
            'axe_od' => 'decimal:2',
            'axe_og' => 'decimal:2',
            'addition_od' => 'decimal:2',
            'addition_og' => 'decimal:2',
            'ecart_pupillaire' => 'decimal:2',
        ];
    }

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_id');
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
}
