<?php

namespace App\Providers;

use App\Http\Middleware\InitializeTenancyByDomain;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $modules = getModules();

        foreach ($modules as $module) {
            // Load the module's autoload file (if it exists)
            $this->autoloadModuleDependencies($module);

            // Get and register the module's service provider
            $provider = $this->getModuleServiceProvider($module);
            if ($provider) {
                $this->app->register($provider);

                $this->loadModuleRoute($module);
            }
        }
    }

    protected function autoloadModuleDependencies($modulePath)
    {
        $vendorAutoload = $modulePath.'/vendor/autoload.php';
        if (file_exists($vendorAutoload)) {
            require_once $vendorAutoload;
        }
    }

    protected function getModuleServiceProvider($modulePath)
    {
        $moduleName = basename($modulePath);
        $providerClass = "Modules\\{$moduleName}\\{$moduleName}ServiceProvider";

        return class_exists($providerClass) ? $providerClass : null;
    }

    private function loadModuleRoute($module)
    {
        $routes = File::files($module.'/routes');
        foreach ($routes as $route) {
            $routeFile = $route->getPathname();

            if (file_exists($routeFile)) {
                Route::prefix('modules')
                    ->middleware([
                        'web',
                        InitializeTenancyByDomain::class,
                        PreventAccessFromCentralDomains::class,
                    ])
                    ->group($routeFile);
            }
        }
    }
}
