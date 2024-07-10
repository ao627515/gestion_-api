<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Autoriser la demande
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lastname' => 'sometimes|required|string|max:255',
            'firstname' => 'sometimes|required|string|max:255',
            'registration_number' => 'sometimes|required|string|unique:users,registration_number,' . $this->route('user'),
            'ref_cinb' => 'sometimes|required|string|unique:users,ref_cinb,' . $this->route('user'),
            'role_id' => 'sometimes|required|exists:user_roles,id',
            'phone' => 'sometimes|required|string|unique:users,phone,' . $this->route('user'),
            'password' => 'sometimes|required|string|min:8',
            'birthday' => 'sometimes|required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'lastname.required' => 'Le nom de famille est obligatoire.',
            'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'lastname.max' => 'Le nom de famille ne doit pas dépasser :max caractères.',
            'firstname.required' => 'Le prénom est obligatoire.',
            'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstname.max' => 'Le prénom ne doit pas dépasser :max caractères.',
            'registration_number.required' => 'Le numéro d\'inscription est obligatoire.',
            'registration_number.unique' => 'Ce numéro d\'inscription est déjà utilisé.',
            'ref_cinb.required' => 'Le numéro CINB est obligatoire.',
            'ref_cinb.unique' => 'Ce numéro CINB est déjà utilisé.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle sélectionné n\'existe pas.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'phone.max' => 'Le numéro de téléphone ne doit pas dépasser :max caractères.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'birthday.required' => 'La date de naissance est obligatoire.',
            'birthday.date' => 'La date de naissance doit être une date valide.',
        ];
    }

    /**
     * Configure the validatorURL instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json($validator->errors(), 422));
    }
}
