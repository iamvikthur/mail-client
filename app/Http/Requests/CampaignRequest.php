<?php

namespace App\Http\Requests;

use App\Enums\CampaignStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
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
            "campaign.store" => [
                "name" => ["required", "string", "max:255"],
                "description" => ["sometimes", "string"],
                "subject" => ["required", "string"],
                "send_time" => ["required", "date_format:Y-m-d H:i:s"],
                "template" => ["sometimes", "string",],
                "contact_list_ids" => ["required", "array"],
                "status" => ["required", "string", Rule::enum(CampaignStatusEnum::class)],
                "cc" => ["sometimes", "array"],
                "bcc" => ["sometimes", "array"],
                "meta" => ["sometimes", "array"]
            ],

            "campaign.update" => [
                "name" => ["sometimes", "string", "max:255"],
                "description" => ["sometimes", "string"],
                "subject" => ["sometimes", "string"],
                "send_time" => ["sometimes", "date_format:Y-m-d H:i:s"],
                "template" => ["sometimes", "string"],
                "contact_list_ids" => ["sometimes", "array"],
                "status" => ["sometimes", "string", Rule::enum(CampaignStatusEnum::class)],
                "cc" => ["sometimes", "array"],
                "bcc" => ["sometimes", "array"],
                "meta" => ["sometimes", "array"]
            ],

            default => []
        };

        return $rules;
    }
}
