<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function sendOk(string $status = 'OK', int $code = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'status' => $status
        ], $code);
    }

    public function sendData(mixed $data, string $status = 'OK', int $code = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'status' => $status,
            'data' => $data
        ], $code);
    }
}
