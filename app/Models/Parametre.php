<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $fillable = [
        'cle',
        'valeur',
        'groupe',
        'description',
        'est_public',
    ];

    protected function casts(): array
    {
        return [
            'est_public' => 'boolean',
        ];
    }
}
