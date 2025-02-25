<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFolderRequest extends FormRequest
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

            "contact_folder.store" => [
                "name" => ["required", "string", "max:255"],
                "meta" => ["sometimes", "array"]
            ],

            "contact_folder.update" => [
                "name" => ["sometimes", "string", "max:255"],
                "meta" => ["sometimes", "array"]
            ],

            default => [],
        };

        return $rules;
    }
}
