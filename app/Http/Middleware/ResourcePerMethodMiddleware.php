<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ResourcePerMethodMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $data)
    {
        $middleware = Str::of($data)->explode('|');
        $permission = '';
        $bool = false;
        foreach ($middleware as $str) {
            $method = Str::of($str)->explode('@')[0];
            if (Str::of($request->route()->getAction('uses'))->explode('@')->last() == $method) {
                $can = Str::of($str)->explode('@')[1];
                $permission = $can;
                $bool = app('auth')
                    ->user()
                    ->can($permission);

                break;
            } else {
                $bool = false;
            }
        }

        if (! $bool) {
            throw UnauthorizedException::forPermissions([$permission]);
        }

        return $next($request);
    }
}
