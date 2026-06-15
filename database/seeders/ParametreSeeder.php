<?php

namespace Database\Seeders;

use App\Models\Parametre;
use Illuminate\Database\Seeder;

class ParametreSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['cle' => 'app.nom',     'valeur' => config('app.name'), 'groupe' => 'app', 'description' => 'Nom affiché de la plateforme', 'est_public' => true],
            ['cle' => 'app.palette', 'valeur' => 'blue',             'groupe' => 'app', 'description' => 'Palette de couleurs active',    'est_public' => true],
            ['cle' => 'app.logo',    'valeur' => null,               'groupe' => 'app', 'description' => 'Chemin vers le logo (storage)', 'est_public' => true],
        ];

        foreach ($defaults as $data) {
            Parametre::updateOrCreate(
                ['cle' => $data['cle']],
                $data
            );
        }
    }
}
