<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuickMail extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['id'];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'recipients' => 'array'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): HasOne
    {
        return $this->hasOne(Template::class);
    }

    public function mailbox(): HasOne
    {
        return $this->hasOne(Mailbox::class);
    }

    public function contactLists(): BelongsToMany
    {
        return $this->belongsToMany(ContactList::class, "contact_list_quick_mail");
    }
}
