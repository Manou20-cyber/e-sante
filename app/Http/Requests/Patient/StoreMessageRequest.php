<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'cabinet_id' => ['required', 'exists:cabinets_optiques,id'],
            'objet' => ['required', 'string', 'max:200'],
            'contenu' => ['required', 'string', 'max:2000'],
        ];
    }
}
