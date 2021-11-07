<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function sendOk($status = 'OK', $code = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'status' => $status
        ], $code);
    }

    public function sendData($data, $status = 'OK', $code = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'status' => $status,
            'data' => $data
        ], $code);
    }
}
