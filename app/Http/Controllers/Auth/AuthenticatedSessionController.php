<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = $request->user();

        $user->tokens()->delete();

        $user->token = $user->generateAuthToken();

        return send_response(true, [$user], MCM_SIGN_IN_SUCCESS);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return send_response(true, [], MCM_SIGN_OUT_SUCCESS);
    }
}
