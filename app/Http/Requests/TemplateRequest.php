<?php

namespace App\Http\Requests;

use App\Enums\TemplatePrivacyEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
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
            "template.store" => [
                "name" => ["required", "string", "max:255"],
                "template" => ["required", "string"],
                "privacy" => ["sometimes", "string", Rule::enum(TemplatePrivacyEnum::class)],
                "meta" => ["sometimes", "array"]
            ],

            "template.update" => [
                "name" => ["sometimes", "string", "max:255"],
                "template" => ["sometimes", "string"],
                "privacy" => ["sometimes", "string", Rule::enum(TemplatePrivacyEnum::class)],
                "meta" => ["sometimes", "array"]
            ],

            default => []
        };

        return $rules;
    }
}
