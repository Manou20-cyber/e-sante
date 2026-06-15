<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreneauHoraire extends Model
{
    protected $table = 'creneaux_horaires';

    protected $fillable = [
        'cabinet_id',
        'opticien_id',
        'prix',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'duree_consultation',
        'capacite_max',
        'est_actif',
        'accepte_video',
    ];

    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
            'accepte_video' => 'boolean',
            'prix' => 'decimal:0',
        ];
    }

    public function opticien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opticien_id');
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
