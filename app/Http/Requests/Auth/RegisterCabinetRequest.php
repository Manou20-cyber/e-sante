<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterCabinetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'cabinet_nom' => ['required', 'string', 'max:255'],
            'cabinet_adresse' => ['required', 'string', 'max:500'],
            'cabinet_ville' => ['required', 'string', 'max:100'],
            'cabinet_code_postal' => ['required', 'string', 'max:10'],
            'cabinet_telephone' => ['required', 'string', 'max:20'],
            'cabinet_email' => ['nullable', 'email', 'max:255'],
            'cabinet_siret' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function attributes(): array
    {
        return [
            'cabinet_nom' => 'nom du cabinet',
            'cabinet_adresse' => 'adresse',
            'cabinet_ville' => 'ville',
            'cabinet_code_postal' => 'code postal',
            'cabinet_telephone' => 'téléphone du cabinet',
        ];
    }
}
