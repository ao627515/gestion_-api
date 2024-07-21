<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccessRightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Règles de validation appliquées à la demande.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'libelle' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
        ];
    }

    /**
     * Messages d'erreur de validation personnalisés.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'libelle.required' => 'Le champ libellé est obligatoire.',
            'libelle.string' => 'Le champ libellé doit être une chaîne de caractères.',
            'libelle.max' => 'Le champ libellé ne peut pas dépasser 255 caractères.',
            'description.required' => 'Le champ description est obligatoire.',
            'description.string' => 'Le champ description doit être une chaîne de caractères.',
        ];
    }

    /**
     * Gérer les erreurs de validation.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'errors' => $validator->errors()
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}

