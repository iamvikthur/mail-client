<?php

namespace App\Http\Controllers\Auth;

use App\Actions\GenerateOTP;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Services\Mail\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return send_response(true, [], MCH_USER_NOT_FOUND_BUT_BE_SILENT);
        }

        // send OTP
        $otp = ""; // (new GenerateOTP())->generate();
        $endpoint = 'api/email-verification';
        $data = ["otp" => $otp];

        // dispatch(new SendEmailJob($email, $endpoint, $data));

        Cache::put(MCH_passwordResetCacheKey($email), $otp, now()->addMinutes(10));

        $message = MCH_OTP_SENT_MESSAGE;
        return send_response(true, [], $message);
    }
}
