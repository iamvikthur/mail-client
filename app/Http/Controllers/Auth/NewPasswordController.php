<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PasswordResetRequest $passwordResetRequest)
    {
        $validatedRequest = $passwordResetRequest->validated();
        $email = $validatedRequest['email'];
        $token = $validatedRequest['token'];
        $cacheKey = MCH_oneTimePasswordCacheKey($email);

        $user = User::where('email', $email)->first();
        $otp = Cache::get($cacheKey);

        if (!$otp) {
            return send_response(false, [], MCH_OTP_EXPIRED, 400);
        }

        if ($otp !== $token) {
            return send_response(false, [], MCH_INVALID_OTP, 400);
        }

        $user->forceFill([
            'password' => Hash::make($passwordResetRequest->string('password')),
            'remember_token' => Str::random(60),
        ])->save();

        // event(new PasswordReset($user));

        Cache::forget($cacheKey);

        return send_response(true, [], MCH_PASSWORD_RESET_SUCCESS);
    }
}
