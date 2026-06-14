<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCabinetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isSuperAdmin = $this->user()?->hasRole('super_admin');

        return [
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string', 'max:500'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['required', 'string', 'max:10'],
            'telephone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'siret' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'supprimer_logo' => ['nullable', 'boolean'],
            'user_id' => $isSuperAdmin ? ['required', 'exists:users,id'] : ['nullable'],
            'est_actif' => $isSuperAdmin ? ['boolean'] : ['nullable'],
        ];
    }
}
