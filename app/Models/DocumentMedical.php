<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentMedical extends Model
{
    protected $fillable = [
        'patient_id',
        'dossier_id',
        'uploaded_by',
        'nom',
        'type',
        'chemin_fichier',
        'mime_type',
        'taille',
        'description',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
