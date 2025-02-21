<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return send_response(true, [], MCH_ACCOUNT_VERIFICATION_LINK_SENT);
    }
}
