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
        'opticien_id',
        'date',
        'duree',
        'type',
        'statut',
        'motif',
        'notes',
        'demande_video',
        'video_room',
        'video_started_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'demande_video' => 'boolean',
            'video_started_at' => 'datetime',
        ];
    }

    public function hasVideoRoom(): bool
    {
        return $this->video_room !== null;
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

    public function opticien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opticien_id');
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class, 'rendezvous_id');
    }
}
