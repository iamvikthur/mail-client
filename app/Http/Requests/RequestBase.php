<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RequestBase extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        throw new ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'Validation failed. Please check the input fields.',
            'data' => ['error' => $errors],
        ], 422));
    }
}
