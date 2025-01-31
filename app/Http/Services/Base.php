<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class Base
{
    protected User $user;
    public function __construct()
    {
        $authenticatable = Auth::user();

        if ($authenticatable == null) {
            throw new AuthenticationException("This service requires an authenticated user");
        }

        $user = User::find($authenticatable->id)->first();
        $this->user = $user;
    }
}
