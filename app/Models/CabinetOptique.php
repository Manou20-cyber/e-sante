<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CabinetOptique extends Model
{
    protected $table = 'cabinets_optiques';

    protected $fillable = [
        'user_id',
        'nom',
        'adresse',
        'ville',
        'code_postal',
        'telephone',
        'email',
        'siret',
        'description',
        'logo',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creneaux(): HasMany
    {
        return $this->hasMany(CreneauHoraire::class, 'cabinet_id');
    }

    public function rendezvous(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'cabinet_id');
    }

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class, 'cabinet_id');
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class, 'cabinet_id');
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'cabinet_id');
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class, 'cabinet_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'cabinet_id');
    }
}
