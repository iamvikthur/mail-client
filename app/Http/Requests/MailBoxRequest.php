<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $mailBoxId = optional($this->route('mail_box'))->id;

        $rules = match ($routeName) {
            "mail_box.store" => [
                "title" => ["required", "string", "max:255"],
                "smtp_host" => ["required", "string", "url"],
                "smtp_port" => ["required", "string", "max:10"],
                "smtp_username" => ["required", "string", "unique:mail_boxes,smtp_username"],
                "smtp_password" => ["required", "string"],
                "smtp_encryption" => ["sometimes", "string"],

                "imap_host" => ["sometimes", "string", "url"],
                "imap_port" => ["sometimes", "string", "max:10"],
                "imap_username" => ["sometimes", "string", "unique:mail_boxes,imap_username"],
                "imap_password" => ["sometimes", "string"],
                "imap_encryption" => ["sometimes", "string"],

                "meta" => ["sometimes", "array"],
            ],
            "mail_box.update" => [
                "title" => ["sometimes", "string", "max:255"],
                "smtp_host" => ["sometimes", "string", "url"],
                "smtp_port" => ["sometimes", "string", "max:10"],
                "smtp_username" => ["sometimes", "string", Rule::unique('mail_boxes', 'smtp_username')->ignore($mailBoxId)],
                "smtp_password" => ["sometimes", "string"],
                "smtp_encryption" => ["sometimes", "string"],

                "imap_host" => ["sometimes", "string", "url"],
                "imap_port" => ["sometimes", "string", "max:10"],
                "imap_username" => ["sometimes", "string", Rule::unique('mail_boxes', 'imap_username')->ignore($mailBoxId)],
                "imap_password" => ["sometimes", "string"],
                "imap_encryption" => ["sometimes", "string"],

                "meta" => ["sometimes", "array"],
            ],

            default => []
        };

        return $rules;
    }
}
