<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCreneauRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jour_semaine' => ['required', 'integer', 'min:1', 'max:7'],
            'heure_debut' => ['required', 'date_format:H:i,H:i:s'],
            'heure_fin' => ['required', 'date_format:H:i,H:i:s', 'after:heure_debut'],
            'duree_consultation' => ['required', 'integer', 'min:15', 'max:120'],
            'capacite_max' => ['nullable', 'integer', 'min:1', 'max:10'],
            'prix' => ['required', 'numeric', 'min:0'],
            'est_actif' => ['boolean'],
        ];
    }
}
