<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'entite_type',
        'entite_id',
        'donnees_avant',
        'donnees_apres',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'donnees_avant' => 'array',
            'donnees_apres' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
