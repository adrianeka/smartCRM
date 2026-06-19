<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function successResponse(
        $data = null,
        string $message = 'Success',
        int $code = 200
    ) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'timestamp' => now(),
                'version' => 'v1',
            ],
        ], $code);
    }

    protected function errorResponse(
        string $message = 'Error',
        int $code = 500,
        $errors = null
    ) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => [
                'timestamp' => now(),
                'version' => 'v1',
            ],
        ], $code);
    }
}
