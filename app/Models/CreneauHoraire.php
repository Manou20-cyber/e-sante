<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreneauHoraire extends Model
{
    protected $fillable = [
        'cabinet_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'duree_consultation',
        'capacite_max',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
        ];
    }

    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(CabinetOptique::class, 'cabinet_id');
    }

    public function rendezvous(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'creneau_id');
    }
}
