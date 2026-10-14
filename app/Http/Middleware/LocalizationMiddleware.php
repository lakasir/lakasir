<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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
        if (! Schema::hasTable('users')) {
            return $next($request);
        }
        $locale = (string) config('setting.default_locale', 'ar');
        $user = auth()->user();
        if ($user) {
            $locale = $user->profile->locale ?: $locale;
        }
        config(['app.locale' => $locale]);
        app()->setLocale($locale);

        if (Schema::hasTable('settings')) {
            $currency = currency_code();
            $moneyLocale = money_locale();
            Table::$defaultCurrency = $currency;
            Table::$defaultNumberLocale = $moneyLocale;
            Infolist::$defaultCurrency = $currency;
            Infolist::$defaultNumberLocale = $moneyLocale;
        }

        return $next($request);
    }
}
