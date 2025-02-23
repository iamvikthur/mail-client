<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $emailVerificationRequest)
    {
        $user = $emailVerificationRequest->user();

        $user->markEmailAsVerified();

        event(new Verified($user));

        Cache::forget(MCH_oneTimePasswordCacheKey($user->email));

        return send_response(true, [], MCH_ACCOUNT_VERIFIED);
    }
}
