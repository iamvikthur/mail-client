<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ContactRequest extends RequestBase
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

        $contactListId = optional($this->route('contact_list'))->id;
        $contactId = optional($this->route('contact'))->id;

        $rules = match ($routeName) {
            "contact_list.contact.store" => [
                "firstname" => ["required_without:contact_file", "string", "max:255"],
                "lastname" => ["required_without:contact_file", "string", "max:255"],
                "email" => ["required_without:contact_file", "string", "email", Rule::unique('contacts')->where('contact_list_id', $contactListId)],
                "contact_file" => ["required_without_all:firstname,lastname,email", "file", "mimes:csv,xlsx"],
                "meta" => ["sometimes", "array"]
            ],

            "contact_list.contact.update" => [
                "firstname" => ["sometimes", "string", "max:255"],
                "lastname" => ["sometimes", "string", "max:255"],
                "email" => ["sometimes", "string", "email", Rule::unique('contacts')->where('contact_list_id', $contactListId)->ignore($contactId)],
                "meta" => ["sometimes", "array"]
            ],

            default => []
        };

        return $rules;
    }

    public function messages()
    {
        return [
            "email.unique" => "This contact list already has a contact with this email"
        ];
    }
}
