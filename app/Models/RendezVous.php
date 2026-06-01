<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RendezVous extends Model
{
    protected $table = 'rendezvous';

    protected $fillable = [
        'patient_id',
        'cabinet_id',
        'creneau_id',
        'date',
        'duree',
        'type',
        'statut',
        'motif',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
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

    public function creneau(): BelongsTo
    {
        return $this->belongsTo(CreneauHoraire::class, 'creneau_id');
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class, 'rendezvous_id');
    }
}
