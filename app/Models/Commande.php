<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    protected $fillable = [
        'patient_id',
        'cabinet_id',
        'ordonnance_id',
        'numero',
        'statut',
        'montant_total',
        'adresse_livraison',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'montant_total' => 'decimal:2',
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

    public function ordonnance(): BelongsTo
    {
        return $this->belongsTo(Ordonnance::class);
    }

    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
            ->withPivot(['quantite', 'prix_unitaire', 'specifications'])
            ->withTimestamps();
    }

    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class);
    }

    public function garanties(): HasMany
    {
        return $this->hasMany(Garantie::class);
    }

    public function retour(): HasOne
    {
        return $this->hasOne(RetourCommande::class);
    }
}
