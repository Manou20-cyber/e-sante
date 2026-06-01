<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenOptique extends Model
{
    protected $table = 'examens_optiques';

    protected $fillable = [
        'consultation_id',
        'patient_id',
        'acuite_od',
        'acuite_og',
        'acuite_od_corrigee',
        'acuite_og_corrigee',
        'tension_od',
        'tension_og',
        'resultats_complementaires',
        'observations',
        'chemin_fichier',
    ];

    protected function casts(): array
    {
        return [
            'resultats_complementaires' => 'array',
            'acuite_od' => 'decimal:2',
            'acuite_og' => 'decimal:2',
            'acuite_od_corrigee' => 'decimal:2',
            'acuite_og_corrigee' => 'decimal:2',
            'tension_od' => 'decimal:2',
            'tension_og' => 'decimal:2',
        ];
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
