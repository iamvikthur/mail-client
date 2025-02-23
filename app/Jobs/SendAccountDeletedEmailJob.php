<?php

namespace App\Jobs;

use App\Mail\AccountDeletedMailable;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAccountDeletedEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user)->send(new AccountDeletedMailable($this->user->firstname));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            throw $th;
        }
    }
}
