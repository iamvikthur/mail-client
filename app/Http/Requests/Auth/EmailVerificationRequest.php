<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\RequestBase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class EmailVerificationRequest extends RequestBase
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
        $verificationToken[] = Cache::get(
            MCH_oneTimePasswordCacheKey(request()->user()->email)
        );

        $rules = match ($routeName) {
            "verification.verify" => [
                "token" => ["required", "string", Rule::in($verificationToken)]
            ],
            "" => [],
            default => []
        };

        return $rules;
    }

    public function messages()
    {
        return [
            "token.in" => "Token expires after 10 mins"
        ];
    }
}
