<?php

namespace App\Services;

use App\Models\Parametre;

class ParametreService
{
    private const CACHE_KEY = 'app_settings';

    private const CACHE_TTL = 3600;

    public static function getSettings(): array
    {
        return cache()->remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                $parametres = Parametre::whereIn('cle', ['app.nom', 'app.logo', 'app.palette'])
                    ->pluck('valeur', 'cle');
            } catch (\Exception) {
                return self::defaults();
            }

            $paletteKey = $parametres->get('app.palette', 'blue');

            return [
                'nom' => $parametres->get('app.nom', config('app.name')),
                'logo' => $parametres->get('app.logo'),
                'palette_key' => $paletteKey,
                'palette' => PaletteService::get($paletteKey),
            ];
        });
    }

    public static function clearCache(): void
    {
        cache()->forget(self::CACHE_KEY);
    }

    public static function defaults(): array
    {
        return [
            'nom' => config('app.name'),
            'logo' => null,
            'palette_key' => 'blue',
            'palette' => PaletteService::default(),
        ];
    }
}
