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
            'price' => ['required_without:consumerTickets', 'numeric', 'min:0'],
            'quantity' => ['numeric', 'min:1'],
            'consumerTickets' => ['required_without:price', 'array'],
            'total' => ['numeric', 'min:0'],
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
            'price.required_without' => 'The price field is required when consumerTickets is not present.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least :min.',

            'total.numeric' => 'The total must be a number.',
            'total.min' => 'The total must be at least :min.',

            'quantity.numeric' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least :min.',

            'consumerTickets.required_without' => 'The consumerTickets field is required when price is not present.',
            'consumerTickets.array' => 'The consumerTickets must be an array.',
        ];
    }

}
