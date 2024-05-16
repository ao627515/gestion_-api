<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'numeric'],
            'password' => ['required'],
            'token_name' => ['required']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation errors',
            'errorsList' => $validator->errors()
        ], 422));
    }

    public function messages()
    {
        return [
            'phone.required' => "the phone number is required",
            'phone.numeric' => "the phone number must be a numeric",
            'password.required' => 'The password is required',
            'token_name.required' => 'The token name is required'
        ];
    }
}
