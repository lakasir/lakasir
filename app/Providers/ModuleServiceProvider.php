<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
}
