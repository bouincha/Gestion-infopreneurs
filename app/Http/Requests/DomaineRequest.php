<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DomaineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100', Rule::unique('domaines', 'nom')->ignore($this->route('domaine'))],
            'description' => 'nullable|string|max:1000',
            'couleur' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }
}
