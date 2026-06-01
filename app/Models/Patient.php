<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'date_naissance',
        'sexe',
        'adresse',
        'ville',
        'code_postal',
        'numero_securite_sociale',
        'medecin_traitant',
    ];

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dossierMedical(): HasOne
    {
        return $this->hasOne(DossierMedical::class);
    }

    public function rendezvous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DocumentMedical::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public function retours(): HasMany
    {
        return $this->hasMany(RetourCommande::class);
    }

    public function examens(): HasMany
    {
        return $this->hasMany(ExamenOptique::class);
    }
}
