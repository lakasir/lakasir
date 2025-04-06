<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UpdateChecker
{
    private string $url;

    public function __construct()
    {
        $this->url = config('app.update_url');
    }

    public function getCurrentVersion(): string
    {
        return trim(file_get_contents(base_path('version.txt')));
    }

    public function getLatestVersion(): ?string
    {
        $response = Http::get($this->url);

        if (! $response->ok()) {
            return null;
        }

        $tag = $response->json('tag_name');

        return ltrim($tag, 'v');
    }

    public function isUpdateAvailable(): bool
    {
        return cache()->remember('update_available_check', now()->addMinutes(60), function () {
            $current = $this->getCurrentVersion();
            $latest = $this->getLatestVersion();

            return $latest && version_compare($latest, $current, '>');
        });
    }

    public function getChangelog(): ?string
    {
        $response = Http::get($this->url);

        return $response->ok() ? $response->json('body') : null;
    }
}
