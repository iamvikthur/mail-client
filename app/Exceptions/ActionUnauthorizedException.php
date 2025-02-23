<?php

namespace App\Exceptions;

use Exception;

class ActionUnauthorizedException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render()
    {
        return send_response(
            false,
            [],
            MCH_ACTION_UNAUTHORIZED,
            401
        );
    }
}
