<?php

namespace App\Services;

class PaletteService
{
    public static function palettes(): array
    {
        return [
            'blue' => [
                'name' => 'Bleu médical',
                'preview' => '#1e3a5f',
                'accent' => '#3b82f6',
                'sidebar_bg' => '#1e3a5f',
                'sidebar_border' => '#1e40af',
                'sidebar_text' => '#bfdbfe',
                'sidebar_muted' => '#93c5fd',
                'sidebar_active' => '#1d4ed8',
                'sidebar_hover' => '#1e3a8a',
                'logo_bg' => '#3b82f6',
                'primary' => '#2563eb',
                'primary_light_bg' => '#eff6ff',
                'primary_light_text' => '#1d4ed8',
            ],
            'teal' => [
                'name' => 'Teal santé',
                'preview' => '#0f3d3d',
                'accent' => '#14b8a6',
                'sidebar_bg' => '#0f3d3d',
                'sidebar_border' => '#0d4f4f',
                'sidebar_text' => '#99f6e4',
                'sidebar_muted' => '#5eead4',
                'sidebar_active' => '#0d9488',
                'sidebar_hover' => '#115e59',
                'logo_bg' => '#14b8a6',
                'primary' => '#0d9488',
                'primary_light_bg' => '#f0fdfa',
                'primary_light_text' => '#0f766e',
            ],
            'emerald' => [
                'name' => 'Vert nature',
                'preview' => '#064e3b',
                'accent' => '#10b981',
                'sidebar_bg' => '#064e3b',
                'sidebar_border' => '#065f46',
                'sidebar_text' => '#a7f3d0',
                'sidebar_muted' => '#6ee7b7',
                'sidebar_active' => '#059669',
                'sidebar_hover' => '#065f46',
                'logo_bg' => '#10b981',
                'primary' => '#059669',
                'primary_light_bg' => '#ecfdf5',
                'primary_light_text' => '#065f46',
            ],
            'purple' => [
                'name' => 'Violet moderne',
                'preview' => '#2e1065',
                'accent' => '#8b5cf6',
                'sidebar_bg' => '#2e1065',
                'sidebar_border' => '#3b0764',
                'sidebar_text' => '#ddd6fe',
                'sidebar_muted' => '#c4b5fd',
                'sidebar_active' => '#7c3aed',
                'sidebar_hover' => '#3b0764',
                'logo_bg' => '#8b5cf6',
                'primary' => '#7c3aed',
                'primary_light_bg' => '#f5f3ff',
                'primary_light_text' => '#5b21b6',
            ],
            'indigo' => [
                'name' => 'Indigo professionnel',
                'preview' => '#1e1b4b',
                'accent' => '#6366f1',
                'sidebar_bg' => '#1e1b4b',
                'sidebar_border' => '#312e81',
                'sidebar_text' => '#c7d2fe',
                'sidebar_muted' => '#a5b4fc',
                'sidebar_active' => '#4338ca',
                'sidebar_hover' => '#312e81',
                'logo_bg' => '#6366f1',
                'primary' => '#4f46e5',
                'primary_light_bg' => '#eef2ff',
                'primary_light_text' => '#3730a3',
            ],
            'rose' => [
                'name' => 'Rose chaleureux',
                'preview' => '#4c0519',
                'accent' => '#f43f5e',
                'sidebar_bg' => '#4c0519',
                'sidebar_border' => '#881337',
                'sidebar_text' => '#fecdd3',
                'sidebar_muted' => '#fda4af',
                'sidebar_active' => '#e11d48',
                'sidebar_hover' => '#881337',
                'logo_bg' => '#f43f5e',
                'primary' => '#e11d48',
                'primary_light_bg' => '#fff1f2',
                'primary_light_text' => '#be123c',
            ],
            'slate' => [
                'name' => 'Ardoise neutre',
                'preview' => '#0f172a',
                'accent' => '#64748b',
                'sidebar_bg' => '#0f172a',
                'sidebar_border' => '#1e293b',
                'sidebar_text' => '#cbd5e1',
                'sidebar_muted' => '#94a3b8',
                'sidebar_active' => '#334155',
                'sidebar_hover' => '#1e293b',
                'logo_bg' => '#475569',
                'primary' => '#475569',
                'primary_light_bg' => '#f8fafc',
                'primary_light_text' => '#334155',
            ],
            'orange' => [
                'name' => 'Orange dynamique',
                'preview' => '#431407',
                'accent' => '#f97316',
                'sidebar_bg' => '#431407',
                'sidebar_border' => '#7c2d12',
                'sidebar_text' => '#fed7aa',
                'sidebar_muted' => '#fdba74',
                'sidebar_active' => '#ea580c',
                'sidebar_hover' => '#7c2d12',
                'logo_bg' => '#f97316',
                'primary' => '#ea580c',
                'primary_light_bg' => '#fff7ed',
                'primary_light_text' => '#c2410c',
            ],
        ];
    }

    public static function get(string $key): array
    {
        return self::palettes()[$key] ?? self::palettes()['blue'];
    }

    public static function default(): array
    {
        return self::palettes()['blue'];
    }
}
