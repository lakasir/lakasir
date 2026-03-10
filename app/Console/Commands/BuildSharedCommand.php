<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BuildSharedCommand extends Command
{
    protected $signature = 'build:shared';
    protected $description = 'Build Laravel Filament project for shared hosting deployment (safe version)';

    public function handle()
    {
        $this->info('🏗️ Building Laravel Filament project for shared hosting (safe mode)...');

        // Step 1: Clear caches
        $this->callSilent('optimize:clear');

        // Step 2: Install dependencies
        $this->info('📦 Installing Composer dependencies (no-dev)...');
        $composer = new Process(['composer', 'install', '--no-dev', '--optimize-autoloader'], base_path());
        $composer->setTimeout(300)->mustRun(fn($type, $buffer) => print $buffer);

        // Step 3: Build frontend (optional)
        if (File::exists(base_path('package.json'))) {
            $this->info('🧱 Building frontend assets...');
            $npm = new Process(['npm', 'run', 'build'], base_path());
            $npm->setTimeout(300)->mustRun(fn($type, $buffer) => print $buffer);
        }

        // Step 4: Prepare output folder
        $buildPath = storage_path('app/shared-release');
        $publicPath = "$buildPath/public_html";
        $laravelPath = "$buildPath/laravel";

        File::deleteDirectory($buildPath);
        File::makeDirectory($publicPath, 0755, true);
        File::makeDirectory($laravelPath, 0755, true);

        // Step 5: Copy Laravel source (exclude heavy/recursive dirs)
        $this->info('📁 Copying Laravel source files...');
        $excluded = [
            'public',
            'node_modules',
            'vendor',
            'tests',
            'storage',
        ];

        foreach (File::directories(base_path()) as $dir) {
            $name = basename($dir);
            if (in_array($name, $excluded)) continue;
            File::copyDirectory($dir, "$laravelPath/$name");
        }

        // Copy only essential storage subfolders
        $this->info('🗂️ Copying storage essentials...');
        $storageEssentialPaths = [
            base_path('storage/app/public'),
            base_path('storage/framework'),
        ];
        foreach ($storageEssentialPaths as $path) {
            if (File::exists($path)) {
                File::copyDirectory($path, str_replace(base_path('storage'), "$laravelPath/storage", $path));
            }
        }

        // Copy root files (except unnecessary ones)
        foreach (File::files(base_path()) as $file) {
            $filename = basename($file);
            if (!in_array($filename, [
                'package-lock.json', 'vite.config.js', 'webpack.mix.js', '.gitignore',
            ])) {
                File::copy($file, "$laravelPath/$filename");
            }
        }

        // Step 6: Copy public to public_html
        File::copyDirectory(public_path(), $publicPath);

        // Step 7: Fix index.php vendor path
        $indexPath = "$publicPath/index.php";
        $index = File::get($indexPath);
        $index = str_replace(
            ["../vendor/autoload.php", "../bootstrap/app.php"],
            ["../laravel/vendor/autoload.php", "../laravel/bootstrap/app.php"],
            $index
        );
        File::put($indexPath, $index);

        // Step 8: Zip it
        $this->info('🗜️ Zipping final release...');
        $zipFile = storage_path('app/shared-release.zip');
        File::delete($zipFile);
        (new Process(['zip', '-r', $zipFile, '.'], $buildPath))
            ->setTimeout(300)
            ->mustRun(fn($type, $buffer) => print $buffer);

        $this->info("\n✅ Build complete!");
        $this->line("File ready at: $zipFile");
        $this->line("Upload `laravel/` to root and `public_html/` to hosting public folder.\n");

        return Command::SUCCESS;
    }
}

