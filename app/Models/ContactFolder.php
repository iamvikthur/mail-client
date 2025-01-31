<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactFolder extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['id'];

    protected function casts(): array
    {
        return [
            'meta' => 'array'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contactLists(): HasMany
    {
        return $this->hasMany(ContactList::class);
    }
}
