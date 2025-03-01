<?php

namespace App\Models;

use App\Enums\CampaignStatusEnum;
use App\Jobs\DispatchCampaignMailJob;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Campaign extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['id'];

    protected function casts(): array
    {
        return [
            "meta" => "array",
            "cc" => "array",
            "bcc" => "array"
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mailbox(): HasOne
    {
        return $this->hasOne(Mailbox::class);
    }

    public function contactLists(): BelongsToMany
    {
        return $this->belongsToMany(ContactList::class, "campaign_contact_list");
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(EmailAttachment::class, "attachmentable");
    }

    public function publish_campaign()
    {
        if ($this->status !== CampaignStatusEnum::PUBLISHED) return;

        dispatch(new DispatchCampaignMailJob($this));
    }
}
