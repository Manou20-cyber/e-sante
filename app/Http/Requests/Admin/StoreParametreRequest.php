<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreParametreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cle' => ['required', 'string', 'max:100', 'unique:parametres,cle'],
            'valeur' => ['nullable', 'string'],
            'groupe' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'est_public' => ['boolean'],
        ];
    }
}
