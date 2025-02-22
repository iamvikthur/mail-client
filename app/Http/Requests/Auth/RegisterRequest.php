<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\RequestBase;
use App\Rules\StrongPasswordRule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends RequestBase
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        ];

        if (app()->environment(['production', 'staging'])) {
            $rules['password'] = [
                'required',
                Password::min(8)->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ];
        } elseif (app()->environment('local')) {
            $rules['password'] = ['required', Password::min(8)->defaults()];
        }

        return $rules;
    }

    /**
     * Attempt to register the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signUp(): User
    {
        return User::create([
            'firstname' => $this->input('firstname'),
            'lastname' => $this->input('lastname'),
            'email' => $this->input('email'),
            'password' => Hash::make($this->input('password')),
        ]);
    }
}
