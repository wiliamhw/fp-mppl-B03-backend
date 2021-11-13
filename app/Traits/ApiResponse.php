<?php

namespace App\Traits;

use App\Exceptions\Concerns\HandleApiExceptions;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    use HandleApiExceptions;

    public function sendOk(string $info = 'OK', int $code = 200): JsonResponse
    {
        return response()->json([
            'info' => $info
        ], $code);
    }

    public function sendData(mixed $data, string $info = 'OK', int $code = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'info' => $info
        ], $code);
    }
}
