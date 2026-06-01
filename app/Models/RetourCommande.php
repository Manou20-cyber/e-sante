<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetourCommande extends Model
{
    protected $table = 'retours_commande';

    protected $fillable = [
        'commande_id',
        'patient_id',
        'raison',
        'statut',
        'montant_rembourse',
        'notes_cabinet',
    ];

    protected function casts(): array
    {
        return [
            'montant_rembourse' => 'decimal:2',
        ];
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
