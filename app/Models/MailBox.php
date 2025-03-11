<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailBox extends Model
{
    use HasUlids;

    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            "meta" => "array"
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setSmtpHostAttribute($value)
    {
        $this->attributes['smtp_host'] = $value
            ? preg_replace("~^(?:https?://)?(?:www\.)?~", '', $value)
            : $value;
    }

    public function setImapHostAttribute($value)
    {
        $this->attributes['imap_host'] = $value
            ? preg_replace("~^(?:https?://)?(?:www\.)?~", '', $value)
            : $value;
    }

    public function getImapPasswordAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setImapPasswordAttribute($value)
    {
        $this->attributes['imap_password'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getSmtpPasswordAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setSmtpPasswordAttribute($value)
    {
        $this->attributes['smtp_password'] = $value ? Crypt::encryptString($value) : null;
    }
    public function smtp_details()
    {
        return [
            "host" => $this->smtp_host,
            "username" => $this->smtp_username,
            "password" => $this->smtp_password,
            "encryption" => $this->smtp_encryption,
            "port" => $this->smtp_port
        ];
    }
    public function imap_details()
    {
        return [
            "host" => $this->imap_host,
            "username" => $this->imap_username,
            "password" => $this->imap_password,
            "encryption" => $this->imap_encryption,
            "port" => $this->imap_port
        ];
    }
}
