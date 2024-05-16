<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => ['required_without:ticket_halls', 'numeric', 'min:0'],
            'ticket_halls' => ['required_without:price', 'array'],
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
            'price.required_without' => 'The price field is required when ticket_halls is not present.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least :min.',
            'ticket_halls.required_without' => 'The ticket_halls field is required when price is not present.',
            'ticket_halls.array' => 'The ticket_halls must be an array.',
        ];
    }

}
