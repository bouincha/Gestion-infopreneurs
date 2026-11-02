<?php

namespace App\Http\Requests;

use App\Models\Projet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'infoproneur_id' => 'required|exists:infoproneurs,id',
            'domaine_id' => 'nullable|exists:domaines,id',
            'statut' => ['required', Rule::in(array_keys(Projet::STATUTS))],
            'budget' => 'nullable|numeric|min:0|max:9999999999',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ];
    }

    public function attributes(): array
    {
        return [
            'infoproneur_id' => 'infoproneur',
            'domaine_id' => 'domaine',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
        ];
    }
}
