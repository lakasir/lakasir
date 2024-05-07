<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Stancl\Tenancy\Contracts\TenantCouldNotBeIdentifiedException;
use Stancl\Tenancy\Middleware\IdentificationMiddleware;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;
use Stancl\Tenancy\Tenancy;

class InitializeTenancyByDomain extends IdentificationMiddleware
{
    /** @var callable|null */
    public static $onFail;

    /** @var Tenancy */
    protected $tenancy;

    /** @var DomainTenantResolver */
    protected $resolver;

    public function __construct(Tenancy $tenancy, DomainTenantResolver $resolver)
    {
        $this->tenancy = $tenancy;
        $this->resolver = $resolver;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $this->initializeTenancy(
            $request, $next, $request->getHost()
        );
    }

    public function initializeTenancy($request, $next, ...$resolverArguments)
    {
        try {
            if (! in_array($request->getHost(), config('tenancy.admin_domains'))) {
                if (config('tenancy.central_domains')[0]) {
                    if (! in_array($request->getHost(), config('tenancy.central_domains'))) {
                        $this->tenancy->initialize(
                            $this->resolver->resolve(...$resolverArguments)
                        );
                    }
                }
            }
        } catch (TenantCouldNotBeIdentifiedException $e) {
            $onFail = static::$onFail ?? function ($e) {
                throw $e;
            };

            return $onFail($e, $request, $next);
        }

        return $next($request);
    }
}
