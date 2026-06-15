<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreRendezVousRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'creneau_id' => ['required', 'exists:creneaux_horaires,id'],
            'date' => ['required', 'date', 'after:yesterday'],
            'heures' => ['required', 'array', 'min:1'],
            'heures.*' => ['required', 'date_format:H:i'],
            'type' => ['required', 'string', 'max:50'],
            'motif' => ['nullable', 'string', 'max:500'],
        ];
    }
}
