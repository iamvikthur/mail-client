<?php

namespace App\Http\Requests;

use App\Enums\QuickMailStateEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuickMailRequest extends FormRequest
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
            "quick_mail.store" => [
                "mailbox_id" => ["required", "string", "exists:mailboxes,id"],
                "template_id" => ["required_without:body", "string", "exists:templates,id"],
                "subject" => ["required", "string", "max:255"],
                "body" => ["required_without:template_id", "string"],
                "recipients" => ["required", "string"],
                "sentTime" => ["required", "date_format:Y-m-d H:i:s"],
                "state" => ["sometimes", Rule::enum(QuickMailStateEnum::class)],
                "meta" => ["sometimes", "array"],
            ],

            "quick_mails.update" => [
                "mailbox_id" => ["sometimes", "string", "exists:mailboxes,id"],
                "template_id" => ["sometimes", "string", "exists:templates,id"],
                "subject" => ["sometimes", "string", "max:255"],
                "body" => ["sometimes", "string"],
                "recipients" => ["sometimes", "string"],
                "sentTime" => ["sometimes", "date_format:Y-m-d H:i:s"],
                "state" => ["sometimes", Rule::enum(QuickMailStateEnum::class)],
                "meta" => ["sometimes", "array"],
            ],

            default => []
        };

        return $rules;
    }
}
