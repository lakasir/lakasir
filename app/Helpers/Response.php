<?php

namespace App\Helpers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class Response
{
    public function success(array $data, $code = 200): JsonResponse
    {
        $data = [
            'success' => true,
            'payload' => $data
        ];

        return response()->json($data, $code);
    }

    public function clientError(array $data, $code = 422): JsonResponse
    {
        $data['success'] = false;

        return response()->json($data, $code);
    }

    public function serverError(array $data, $code = 500): JsonResponse
    {
        $data['success'] = false;

        return response()->json($data, $code);
    }
}
