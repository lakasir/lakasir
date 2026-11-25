<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Resolve the current company. The POS app addresses a shop by its domain, so
 * the host wins when it matches; the browser uses the {company_id} route
 * parameter. Whichever identifies the company, the user must be attached to it
 * before it becomes current. Nothing to identify is not an error here — the
 * panel falls back to SetCurrentCompany.
 */
class IdentifyCompany
{
    public function handle(Request $request, Closure $next)
    {
        $company = $this->byDomain($request) ?? $this->byRoute($request);

        if (! $company) {
            if ($request->route('company_id') !== null) {
                throw new NotFoundHttpException;
            }

            return $next($request);
        }

        $user = $request->user();

        if ($user && ! $user->companies()->whereKey($company->id)->exists()) {
            abort(403);
        }

        $company->makeCurrent();

        $request->route()?->forgetParameter('company_id');

        return $next($request);
    }

    private function byDomain(Request $request): ?Company
    {
        return Company::where('domain', $request->getHost())
            ->where('enabled', true)
            ->first();
    }

    private function byRoute(Request $request): ?Company
    {
        $id = $request->route('company_id');

        if ($id === null) {
            return null;
        }

        return Company::whereKey($id)->where('enabled', true)->first();
    }
}
