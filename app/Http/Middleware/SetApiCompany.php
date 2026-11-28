<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\Tenants\User;
use Closure;
use Illuminate\Http\Request;

/**
 * The POS app's company, once the bearer token says who is asking. A shop
 * domain already picked by IdentifyCompany must be one of the user's shops;
 * otherwise the `X-Company-Id` header picks one, or the user's first shop.
 * A user with none is refused, so no API query ever runs without a company.
 */
class SetApiCompany
{
    public const HEADER = 'X-Company-Id';

    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            static::enter($request, $user);
        }

        return $next($request);
    }

    public static function enter(Request $request, User $user): Company
    {
        $requested = $request->header(self::HEADER);

        $company = $user->resolveCompany(
            Company::currentId() ?? (is_numeric($requested) ? (int) $requested : null),
        );

        abort_if($company === null, 403, __('You do not belong to this shop.'));

        return $company->makeCurrent();
    }
}
