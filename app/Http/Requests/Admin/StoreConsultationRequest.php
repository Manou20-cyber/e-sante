<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequest extends FormRequest
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
            'medecin_id' => ['required', 'exists:users,id'],
            'rendezvous_id' => ['nullable', 'exists:rendezvous,id'],
            'date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:50'],
            'diagnostic' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'montant' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
