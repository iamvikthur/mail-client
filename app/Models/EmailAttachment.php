<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmailAttachment extends Model
{
    public function attachmentable(): MorphTo
    {
        return $this->morphTo();
    }
}
