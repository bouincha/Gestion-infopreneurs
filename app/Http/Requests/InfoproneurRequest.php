<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InfoproneurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom_complet' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:30',
            'date_naissance' => 'required|date|before:today',
            'domaine_id' => 'nullable|exists:domaines,id',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:2000',
            'supprimer_photo' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom_complet' => 'nom complet',
            'date_naissance' => 'date de naissance',
            'domaine_id' => 'domaine',
        ];
    }
}
