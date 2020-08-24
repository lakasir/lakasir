<?php

namespace App\Http\Controllers\Api\Auth;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Login as Request;
use App\Repositories\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param App\Repositories\User user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request): JsonResponse
    {
        $user = $this->user->getModel()::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response['token'] = $token;
                $response['message'] = 'Login Success';

                return Response::success($response);
            } else {
                $errors = ['message' => __('app.auth.password.missmatch')];

                return Response::clientError($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            $errors = ['message' => __('app.auth.user.doesnotexist')];

            return Response::clientError($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
