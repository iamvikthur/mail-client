<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;

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

        $user->sendEmailVerificationNotification();

        $user->token = $user->generateAuthToken();

        return send_response(true, [$user], MCH_ACCOUNT_CREATION_SUCCESS);
    }
}
