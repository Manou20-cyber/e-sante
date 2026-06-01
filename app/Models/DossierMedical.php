<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DossierMedical extends Model
{
    protected $fillable = [
        'patient_id',
        'antecedents',
        'allergies',
        'traitements_en_cours',
        'notes',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ordonnances(): HasMany
    {
        return $this->hasMany(Ordonnance::class, 'dossier_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DocumentMedical::class, 'dossier_id');
    }
}
