<?php

namespace App\Services;

use App\Enums\TemplatePrivacyEnum;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Base
{
    protected User $user;
    public function __construct()
    {
        $authenticatable = Auth::user();

        if ($authenticatable == null) {
            throw new AuthenticationException("This service requires an authenticated user");
        }

        $user = User::find($authenticatable->id)->first();
        $this->user = $user;
    }

    protected function generateUniqueSlug(
        string $title,
        Model $model,
        string $column = 'slug',
        int $maxAttempts = 10
    ): string {

        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $attempt = 1;

        while ($attempt <= $maxAttempts) {

            $exists = $model
                ->where($column, $slug)
                ->exists();

            if (!$exists) {
                return $slug;
            }

            $slug = $baseSlug . '-' . Str::random(4);
            $attempt++;
        }

        return $baseSlug . '-' . time();
    }

    protected function canUseTemplate(string $templateId)
    {
        $template = Template::find($templateId);
        if ($template->user->id !== $this->user->id && $template->privacy !== TemplatePrivacyEnum::PUBLIC) {
            return false;
        }

        return true;
    }
}
