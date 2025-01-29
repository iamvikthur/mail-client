<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailBoxRequest extends FormRequest
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
            "email_sending_details.store" => [
                "name_tag" => ["required", "string", "max:255"],
                "host" => ["required", "string", "url"],
                "port" => ["required", "string", "max:10"],
                "username" => ["required", "string"],
                "password" => ["required", "string"],
                "encryption" => ["required", "string"],
                "meta" => ["sometimes", "array"],
            ],
            "email_sending_details.update" => [
                "name_tag" => ["sometimes", "string", "max:255"],
                "host" => ["sometimes", "string", "url"],
                "port" => ["sometimes", "string", "max:10"],
                "username" => ["sometimes", "string"],
                "password" => ["sometimes", "string"],
                "encryption" => ["sometimes", "string"],
                "meta" => ["sometimes", "array"],
            ],

            default => []
        };

        return $rules;
    }
}
