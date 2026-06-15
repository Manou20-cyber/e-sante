<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cabinet_id' => ['required', 'exists:cabinets_optiques,id'],
            'ordonnance_id' => ['nullable', 'exists:ordonnances,id'],
            'produits' => ['required', 'array', 'min:1'],
            'produits.*.id' => ['required', 'exists:produits,id'],
            'produits.*.quantite' => ['required', 'integer', 'min:1', 'max:99'],
            'adresse_livraison' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'produits.required' => 'Veuillez sélectionner au moins un produit.',
            'produits.min' => 'Veuillez sélectionner au moins un produit.',
        ];
    }
}
