<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            "contact.store" => [
                "firstname" => ["required_without:contact_file", "string", "max:255"],
                "lastname" => ["required_without:contact_file", "string", "max:255"],
                "email" => ["required_without:contact_file", "string", "email", "unique:contacts"],
                "contact_file" => ["required_with_all:firstname,lastname,email", "file", "mimes:csv,xlsx"],
                "meta" => ["sometimes", "array"]
            ],

            "contact.update" => [
                "firstname" => ["sometimes", "string", "max:255"],
                "lastname" => ["sometimes", "string", "max:255"],
                "email" => ["sometimes", "string", "email", "unique:contacts"],
                "meta" => ["sometimes", "array"]
            ],

            default => []
        };

        return $rules;
    }
}
