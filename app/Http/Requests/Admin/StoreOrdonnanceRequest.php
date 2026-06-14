<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdonnanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'consultation_id' => ['nullable', 'exists:consultations,id'],
            'sphere_od' => ['nullable', 'numeric', 'between:-30,30'],
            'sphere_og' => ['nullable', 'numeric', 'between:-30,30'],
            'cylindre_od' => ['nullable', 'numeric', 'between:-10,10'],
            'cylindre_og' => ['nullable', 'numeric', 'between:-10,10'],
            'axe_od' => ['nullable', 'integer', 'between:0,180'],
            'axe_og' => ['nullable', 'integer', 'between:0,180'],
            'addition_od' => ['nullable', 'numeric', 'between:0,4'],
            'addition_og' => ['nullable', 'numeric', 'between:0,4'],
            'ecart_pupillaire' => ['nullable', 'numeric', 'between:50,80'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
