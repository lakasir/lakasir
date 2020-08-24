<?php

namespace App\Http\Controllers\Api\Auth;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Login as Request;
use App\Repositories\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class Profile extends Controller
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
     * Profile Auth User
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function get(): JsonResponse
    {
        $user = auth()->user();
        $data = $user->load('profile', 'profile.media');

        return Response::success($data->toArray());
    }
}
