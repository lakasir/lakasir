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

    /**
     *
     * Login User
     *
     * @params App\Http\Requests\Auth\Login as Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $user = $this->user->query()->where('email', $request->email)->first();
        $permission = [
            'create-selling',
            'browse-selling',
            'delete-selling',
            'update-selling',
            'bulk-delete-selling',
            'create-profile',
            'browse-profile'
        ];
        $check = $user->hasAllPermissions($permission);
        if ($check) {
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response['token'] = $token;
                    $response['message'] = 'Login Success';

                    return Response::success($response);
                } else {
                    $errors = ['errors' => [
                        'password' => [ __('app.auth.password.missmatch') ]
                    ]];

                    return Response::clientError($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
            } else {
                $errors = ['errors' => [
                    'email' => [__('app.auth.user.doesnotexist')]
                ]];

                return Response::clientError($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            $errors = ['errors' => [
                'email' => [__('app.auth.user.is_not_cashier')]
            ]];

            return Response::clientError($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
