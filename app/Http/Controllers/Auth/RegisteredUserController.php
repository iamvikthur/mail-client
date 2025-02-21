<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;

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

        dispatch(function () use ($user) {
            event(new Registered($user));
        })->delay(now());


        $user->token = $user->generateAuthToken();

        return send_response(true, [$user], MCM_ACCOUNT_CREATION_SUCCESS);
    }
}
