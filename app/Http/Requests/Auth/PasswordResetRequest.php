<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules;
use App\Http\Requests\RequestBase;

class PasswordResetRequest extends RequestBase
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
        $routeName = $this->route()->getName();

        $rules = match ($routeName) {
            "password.email" => [
                "email" => ["required", "string", "email", "exists:users,email"]
            ],
            "password.store" => [
                'token' => ["required", "string"],
                'email' => ["required", "string", "email", "exists:users,email"],
            ],
            default => []
        };

        if ($routeName === "password.store") {
            $rules['password'] = app()->environment("production", "staging") ? [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->numbers()
                    ->symbols()
                    ->max(16)
                    ->mixedCase()
                    ->uncompromised()
            ] : ['required', 'confirmed', Rules\Password::defaults()];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            "email.exists" => "This email address is invalid"
        ];
    }
}
