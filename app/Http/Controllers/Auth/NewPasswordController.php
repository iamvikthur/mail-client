<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->email;
        $token = $request->token;
        $user = User::where('email', $email)->first();
        $otp = Cache::get(MCH_passwordResetCacheKey($email));

        if (!$user) {
            return send_response(false, [], MCH_INVALID_USER_EMAIL, 400);
        }

        if (!$otp) {
            return send_response(false, [], MCH_OTP_EXPIRED, 400);
        }

        if ($otp !== $token) {
            return send_response(false, [], MCH_INVALID_OTP, 400);
        }

        $user->forceFill([
            'password' => Hash::make($request->string('password')),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        return send_response(true, [], MCH_PASSWORD_RESET_SUCCESS);
    }
}
