<?php

namespace App\Http\Requests\Admin;

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
            'patient_id' => ['required', 'exists:patients,id'],
            'cabinet_id' => ['required', 'exists:cabinets_optiques,id'],
            'ordonnance_id' => ['nullable', 'exists:ordonnances,id'],
            'adresse_livraison' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
            'produits' => ['required', 'array', 'min:1'],
            'produits.*.id' => ['required', 'exists:produits,id'],
            'produits.*.quantite' => ['required', 'integer', 'min:1'],
        ];
    }
}
