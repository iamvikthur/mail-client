<?php

namespace App\Jobs;

use App\Mail\VerifyEmailMailable;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOneTimePasswordJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $token,
        private string $key,
        private User $user,
        private int $expiry = 10
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user)
                ->send(
                    new VerifyEmailMailable(
                        $this->token,
                        $this->user->firstname
                    )
                );

            Cache::put($this->key, $this->token, now()->addMinutes($this->expiry));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            //throw $th;
        }
    }
}
