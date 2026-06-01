<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'cabinet_id',
        'contenu',
        'objet',
        'lu_at',
    ];

    protected function casts(): array
    {
        return [
            'lu_at' => 'datetime',
        ];
    }

    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(CabinetOptique::class, 'cabinet_id');
    }

    public function estLu(): bool
    {
        return $this->lu_at !== null;
    }
}
