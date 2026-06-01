<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    protected $fillable = [
        'cabinet_id',
        'libelle',
        'description',
        'reference',
        'prix',
        'stock',
        'stock_alerte',
        'categorie',
        'marque',
        'images',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'images' => 'array',
            'est_actif' => 'boolean',
        ];
    }

    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(CabinetOptique::class, 'cabinet_id');
    }

    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
            ->withPivot(['quantite', 'prix_unitaire', 'specifications'])
            ->withTimestamps();
    }

    public function garanties(): HasMany
    {
        return $this->hasMany(Garantie::class);
    }
}
