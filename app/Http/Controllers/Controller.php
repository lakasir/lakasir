<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/** @package App\Http\Controllers */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array | Collection $data
     * @param string $message
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function success($data, $message = ""): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], 200);
    }

    /**
     * @param array | Collection $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     * @throws Exception
     * @throws BindingResolutionException
     */
    public function fail($data, $message = "", $code = 500): JsonResponse
    {
        if (is_string($code)) {
            $code = 500;
        }
        if ($code == 200) {
            throw new Exception("Code should not have 200");
        }
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}
