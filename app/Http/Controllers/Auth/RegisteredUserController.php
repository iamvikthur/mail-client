<?php

namespace App\Http\Controllers\Auth;

use App\Actions\GenerateOTP;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyEmailMailable;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $registerRequest)
    {
        $user = $registerRequest->signUp();

        $token = (new GenerateOTP())->generate();

        dispatch(function () use ($token, $user) {
            Mail::to($user)->send(new VerifyEmailMailable($token, $user->firstname));
        })->delay(now());


        $user->token = $user->generateAuthToken();

        return send_response(true, [$user], MCM_ACCOUNT_CREATION_SUCCESS);
    }
}
