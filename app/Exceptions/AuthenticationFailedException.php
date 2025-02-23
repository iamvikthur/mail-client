<?php

namespace App\Exceptions;

use Exception;

class AuthenticationFailedException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render()
    {
        return send_response(
            false,
            [],
            "These credentials do not match our records.",
            401
        );
    }
}
