<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'consultation_id' => ['required', 'exists:consultations,id'],
            'patient_id' => ['required', 'exists:patients,id'],
            'acuite_od' => ['nullable', 'numeric', 'between:0,20'],
            'acuite_og' => ['nullable', 'numeric', 'between:0,20'],
            'acuite_od_corrigee' => ['nullable', 'numeric', 'between:0,20'],
            'acuite_og_corrigee' => ['nullable', 'numeric', 'between:0,20'],
            'tension_od' => ['nullable', 'numeric', 'between:0,100'],
            'tension_og' => ['nullable', 'numeric', 'between:0,100'],
            'observations' => ['nullable', 'string', 'max:3000'],
        ];
    }
}
