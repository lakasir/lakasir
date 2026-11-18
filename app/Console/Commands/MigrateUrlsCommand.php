<?php

namespace App\Console\Commands;

use App\Models\Tenants\About;
use App\Models\Tenants\Product;
use App\Models\Tenants\Profile;
use App\Models\Tenants\UploadedFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateUrlsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-urls {--force : Re-run even if already applied}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert full URLs in database columns to relative paths for dynamic disk support';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $force = $this->option('force');

        $this->info('Migrating stored URLs to relative paths...');

        $this->migrateUploadedFiles($force);

        $this->migrateProductsHeroImages($force);

        $this->migrateProfilesPhoto($force);

        $this->migrateAboutsPhoto($force);

        $this->info("\nMigration complete!");

        return self::SUCCESS;
    }

    /**
     * Strip the disk's base URL from a full URL to produce a relative path.
     */
    private function stripUrl(string $url, string $diskName): string
    {
        $baseUrl = Storage::disk($diskName)->url('/');
        $baseUrl = rtrim($baseUrl, '/');

        if (Str::startsWith($url, $baseUrl)) {
            return Str::after($url, $baseUrl . '/');
        }

        // Handle app-relative paths like /storage/profile/foo.jpg before the HTTP guard
        if (Str::startsWith($url, '/storage/')) {
            return Str::after($url, '/storage/');
        }

        if (!Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        $parsed = parse_url($url);
        if (isset($parsed['path'])) {
            $path = ltrim($parsed['path'], '/');
            $path = Str::remove('storage/', $path);
            $path = Str::remove('tmp/', $path);
            return $path;
        }

        return $url;
    }

    private function migrateUploadedFiles(bool $force): void
    {
        $query = UploadedFile::query();

        if (!$force) {
            $query->where(function ($q) {
                $q->whereNull('relative_path')->orWhere('relative_path', '');
            })->whereNotNull('url');
        } else {
            $this->warn('  --force: re-running migration for ALL uploaded_files records.');
        }

        $files = $query->get();
        $this->info("\n[uploaded_files] Processing {$files->count()} record(s)...");

        $updated = 0;
        foreach ($files as $file) {
            $disk = $file->disk ?: 'public';
            $relativePath = $this->stripUrl($file->attributes['url'] ?? '', $disk);

            if (empty($relativePath) && $file->name) {
                $relativePath = $file->name;
            }

            if (empty($relativePath)) {
                $this->warn("  Skipping ID {$file->id}: could not determine relative path.");
                continue;
            }

            $file->update(['relative_path' => $relativePath]);
            $updated++;
        }

        $this->info("  Updated {$updated} record(s).");
    }

    private function migrateProductsHeroImages(bool $force): void
    {
        $products = Product::whereNotNull('hero_images')->get();
        $this->info("\n[products] Processing {$products->count()} product(s)...");

        $updated = 0;
        foreach ($products as $product) {
            $heroImages = $product->getRawOriginal('hero_images');
            if (empty($heroImages)) {
                continue;
            }

            $urls = explode(',', $heroImages);
            $relativePaths = [];

            foreach ($urls as $url) {
                $url = trim($url);
                if (empty($url)) {
                    continue;
                }

                $uploadedFile = UploadedFile::where('url', $url)->first();
                $disk = $uploadedFile?->disk ?? 'public';

                $relativePaths[] = $this->stripUrl($url, $disk);
            }

            if (!empty($relativePaths)) {
                $product->hero_images = implode(',', $relativePaths);
                $product->save();
                $updated++;
            }
        }

        $this->info("  Updated {$updated} product(s).");
    }

    private function migrateProfilesPhoto(bool $force): void
    {
        $profiles = Profile::whereNotNull('photo')->get();
        $this->info("\n[profiles] Processing {$profiles->count()} profile(s)...");

        $updated = 0;
        foreach ($profiles as $profile) {
            $photo = $profile->getRawOriginal('photo');
            if (empty($photo)) {
                continue;
            }

            $uploadedFile = UploadedFile::where('url', $photo)->first();
            $disk = $uploadedFile?->disk ?? 'public';
            $relativePath = $this->stripUrl($photo, $disk);

            if ($relativePath !== $photo) {
                $profile->photo = $relativePath;
                $profile->save();
                $updated++;
            }
        }

        $this->info("  Updated {$updated} profile(s).");
    }

    private function migrateAboutsPhoto(bool $force): void
    {
        $abouts = About::whereNotNull('photo')->get();
        $this->info("\n[abouts] Processing {$abouts->count()} about record(s)...");

        $updated = 0;
        foreach ($abouts as $about) {
            $photo = $about->getRawOriginal('photo');
            if (empty($photo)) {
                continue;
            }

            $uploadedFile = UploadedFile::where('url', $photo)->first();
            $disk = $uploadedFile?->disk ?? 'public';
            $relativePath = $this->stripUrl($photo, $disk);

            if ($relativePath !== $photo) {
                $about->photo = $relativePath;
                $about->save();
                $updated++;
            }
        }

        $this->info("  Updated {$updated} about record(s).");
    }
}
