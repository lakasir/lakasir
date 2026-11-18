<?php

namespace App\Http\Controllers;

use App\Services\ApiResponseService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data, $message = ''): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], 200);
    }

    protected function fail($data, $message = '', $code = 500): JsonResponse
    {
        if (is_string($code) || $code == 0) {
            $code = 500;
        }
        if ($code == 200) {
            throw new Exception('Code should not have 200');
        }

        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    protected function notFound($message = ''): JsonResponse
    {
        return $this->fail([], $message, 404);
    }

    public function buildResponse(): ApiResponseService
    {
        return new ApiResponseService();
    }

    protected function resolvePerPage(Request $request): ?int
    {
        if (! $request->has('per_page')) {
            return null;
        }

        $perPage = filter_var($request->query('per_page'), FILTER_VALIDATE_INT);

        if ($perPage === false) {
            return null;
        }

        return max(1, min($perPage, 100));
    }
}
