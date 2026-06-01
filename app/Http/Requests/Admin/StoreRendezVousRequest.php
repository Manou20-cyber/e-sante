<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRendezVousRequest extends FormRequest
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
            'creneau_id' => ['nullable', 'exists:creneaux_horaires,id'],
            'date' => ['required', 'date', 'after:now'],
            'duree' => ['required', 'integer', 'min:15', 'max:180'],
            'type' => ['required', 'string', 'max:50'],
            'motif' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
