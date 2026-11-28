<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'localhost:3000/*',
        'livewire/*',
        // Server-to-server from lakasir-cloud, authenticated by a shared
        // secret, never by a browser session cookie.
        'api/cloud/provision',
    ];
}
