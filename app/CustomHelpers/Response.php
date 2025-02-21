<?php

if (!function_exists('send_response')) {
    function send_response(bool $status, array $data, string $message, $statusCode = 200)
    {
        $responseData = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($responseData, $statusCode);
    }
}
