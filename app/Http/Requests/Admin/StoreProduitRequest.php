<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cabinet_id' => ['required', 'exists:cabinets_optiques,id'],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'reference' => ['nullable', 'string', 'max:50'],
            'prix' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_alerte' => ['required', 'integer', 'min:0'],
            'categorie' => ['required', 'in:monture,lentille,verre,accessoire,autre'],
            'marque' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
