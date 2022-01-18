<?php

namespace App\Http\Middleware;

use App\Facades\Response;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param string[][] $guards
     * @return mixed
     * @throws HttpResponseException
     * @throws BindingResolutionException
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (in_array('api', $guards)) {
            if (!(bool)$request->headers->get('is-loged-in')) {
                throw new HttpResponseException(
                    Response::clientError(['message' => 'This action is unauthorized.'], JsonResponse::HTTP_UNAUTHORIZED)
                );
            } else {
                try {
                    $hashSecureAuth = $request->headers->get('secure-auth');
                    $decodedSecureAuth = json_decode(Crypt::decrypt($hashSecureAuth), 1);
                    $username = $decodedSecureAuth['username'];
                    $password = Crypt::decrypt($decodedSecureAuth['password']);
                    /** @var \App\Models\User $user  */
                    $user = User::where('username', $username)->first();
                    if (!$user) {
                        throw new Exception('User is not exist.');
                    }
                    $checked = Hash::check($password, $user->password);
                    if (!$checked) {
                        throw new Exception('User is not exist.');
                    }
                    Auth::login($user);
                } catch (Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                }
            }
        } else {
            $this->authenticate($request, $guards);
        }

        return $next($request);
    }
}
