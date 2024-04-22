<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
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
        $locale = 'en';
        $user = Filament::auth()->user() ?? auth()->user();
        if ($user) {
            $locale = $user->profile->locale ?? 'en';
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
