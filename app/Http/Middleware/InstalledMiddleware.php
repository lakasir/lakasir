<?php

namespace App\Http\Middleware;

use Closure;

class InstalledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!env('INSTALL')) {
            return redirect('install?tab=database');
        }
        return $next($request);
    }
}
