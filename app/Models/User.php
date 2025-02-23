<?php

namespace App\Models;

use App\Actions\GenerateOTP;
use App\Jobs\SendOneTimePasswordJob;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function generateAuthToken()
    {
        $token = $this->createToken($this->name . now())->plainTextToken;

        return $token;
    }

    public function markEmailAsVerified()
    {
        $this->updateQuietly(["email_verified_at" => now()]);
    }

    public function sendEmailVerificationNotification()
    {
        $token = (new GenerateOTP())->generate();
        $key = MCH_oneTimePasswordCacheKey($this->email);

        dispatch(new SendOneTimePasswordJob($token, $key, $this))->delay(now());
    }

    public function mailBoxes(): HasMany
    {
        return $this->hasMany(MailBox::class);
    }

    public function contactFolders(): HasMany
    {
        return $this->hasMany(ContactFolder::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function quickMails(): HasMany
    {
        return $this->hasMany(QuickMail::class);
    }
}
