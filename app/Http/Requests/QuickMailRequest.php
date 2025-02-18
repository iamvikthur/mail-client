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
                "recipients" => ["required_without:contact_list_id", "string"],
                "contact_list_id" => ["required_without:recipients", "string"],
                "send_ime" => ["sometimes", "date_format:Y-m-d H:i:s"],
                "state" => ["sometimes", Rule::enum(QuickMailStateEnum::class)],
                "cc" => ["sometimes", "array"],
                "bcc" => ["sometimes", "array"],
                "meta" => ["sometimes", "array"],
            ],

            "quick_mails.update" => [
                "mailbox_id" => ["sometimes", "string", "exists:mailboxes,id"],
                "template_id" => ["sometimes", "string", "exists:templates,id"],
                "subject" => ["sometimes", "string", "max:255"],
                "body" => ["sometimes", "string"],
                "recipients" => ["sometimes", "string"],
                "contact_list_id" => ["sometimes", "string"],
                "send_ime" => ["sometimes", "date_format:Y-m-d H:i:s"],
                "state" => ["sometimes", Rule::enum(QuickMailStateEnum::class)],
                "cc" => ["sometimes", "array"],
                "bcc" => ["sometimes", "array"],
                "meta" => ["sometimes", "array"],
            ],

            default => []
        };

        return $rules;
    }

    public function messages()
    {
        return [
            "recipients.required" => "Please provide a list of recipients or a contact list",
            "contact_list_id.required" => "Please provide a list of recipients or a contact list",
            "mailbox_id.required" => "Please provide provide a maibox to use for sending this mail",
            "template_id.required" => "Please provide the mailbody or choose a template",
            "subject.required" => "The field subject is required",
            "subject.string" => "The subject string must be a string"
        ];
    }
}
