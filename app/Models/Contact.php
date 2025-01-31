<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['id'];

    protected function casts(): array
    {
        return [
            'meta' => 'array'
        ];
    }

    public function contactList(): BelongsTo
    {
        return $this->belongsTo(ContactList::class);
    }
}
