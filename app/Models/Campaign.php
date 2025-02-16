<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['id'];

    protected function casts(): array
    {
        return [
            "meta" => "array"
        ];
    }

    public function contactLists(): BelongsToMany
    {
        return $this->belongsToMany(ContactList::class, "campaign_contact_list");
    }
}
