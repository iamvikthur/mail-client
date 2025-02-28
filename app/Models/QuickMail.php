<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class QuickMail extends Model
{
    use HasUlids, HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'recipients' => 'array',
            'cc' => 'array',
            'bcc' => 'array'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function mailBox(): BelongsTo
    {
        return $this->belongsTo(Mailbox::class);
    }

    public function contactLists(): BelongsToMany
    {
        return $this->belongsToMany(ContactList::class, "contact_list_quick_mail");
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(EmailAttachment::class, "attachmentable");
    }
}
