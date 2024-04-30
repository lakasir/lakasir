<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ResourcePerMethodMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $data)
    {
        $middleware = Str::of($data)->explode("|");
        $permission = "";
        $bool = false;
        foreach ($middleware as $str) {
            $method = Str::of($str)->explode("@")[0];
            if (Str::of($request->route()->getAction('uses'))->explode("@")->last() == $method) {
                $can = Str::of($str)->explode("@")[1];
                $permission = $can;
                $bool = app("auth")
                    ->user()
                    ->can($permission);

                break;
            } else {
                $bool = false;
            }
        }

        if (!$bool) {
            throw UnauthorizedException::forPermissions([$permission]);
        }
        return $next($request);
    }
}
