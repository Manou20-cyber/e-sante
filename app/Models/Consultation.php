<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    protected $fillable = [
        'rendezvous_id',
        'patient_id',
        'cabinet_id',
        'medecin_id',
        'date',
        'type',
        'diagnostic',
        'notes',
        'montant',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'montant' => 'decimal:2',
        ];
    }

    public function rendezvous(): BelongsTo
    {
        return $this->belongsTo(RendezVous::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(CabinetOptique::class, 'cabinet_id');
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function examen(): HasOne
    {
        return $this->hasOne(ExamenOptique::class);
    }

    public function ordonnances(): HasMany
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class);
    }
}
