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

        $company = $this->fromSession($request, $user)
            ?? $user->companies()->where('enabled', true)->orderBy('id')->first();

        if (! $company) {
            return $next($request);
        }

        $request->session()->put('company_id', $company->id);

        $company->makeCurrent();

        return $next($request);
    }

    private function fromSession(Request $request, $user): ?Company
    {
        $id = $request->session()->get('company_id');

        if (! $id) {
            return null;
        }

        return $user->companies()->whereKey($id)->where('enabled', true)->first();
    }
}
