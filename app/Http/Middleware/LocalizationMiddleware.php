<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $centralDomain = config('tenancy.central_domains')[0];
        if ($centralDomain !== null && $request->host() != $centralDomain) {
            $locale = 'en';
            $user = auth()->user();
            if ($user) {
                $locale = $user->profile->locale ?? 'en';
            }
            config(['app.locale' => $locale]);
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
