<?php

namespace App\Http\Controllers\Auth;

use App\Actions\GenerateOTP;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Jobs\SendOneTimePasswordJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PasswordResetRequest $passwordResetRequest)
    {

        $email = $passwordResetRequest->validated()['email'];

        $user = User::where('email', $email)->first();

        // send OTP
        $token = (new GenerateOTP())->generate();
        $key = MCH_oneTimePasswordCacheKey($user->email);

        dispatch(new SendOneTimePasswordJob($token, $key, $user))->delay(now());

        return send_response(true, [], MCH_OTP_SENT_MESSAGE);
    }
}
