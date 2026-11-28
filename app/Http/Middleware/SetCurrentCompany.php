<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;

/**
 * The panel is entered at /member with no company in the URL, so the company
 * comes from the session and falls back to the user's first enabled one.
 */
class SetCurrentCompany
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $sessionId = $request->session()->get('company_id');

        $company = ($sessionId ? $user->resolveCompany((int) $sessionId) : null)
            ?? $user->resolveCompany();

        if (! $company) {
            return $next($request);
        }

        $request->session()->put('company_id', $company->id);

        $company->makeCurrent();

        return $next($request);
    }
}
