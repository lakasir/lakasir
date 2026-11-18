<?php

use App\Services\UpdateChecker;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->versionPath = base_path('version.txt');
    $this->hasOriginalVersionFile = File::exists($this->versionPath);
    $this->originalVersionContent = $this->hasOriginalVersionFile ? File::get($this->versionPath) : null;
});

afterEach(function () {
    if ($this->hasOriginalVersionFile) {
        File::put($this->versionPath, (string) $this->originalVersionContent);

        return;
    }

    if (File::exists($this->versionPath)) {
        File::delete($this->versionPath);
    }
});

test('it returns Development when version file does not exist', function () {
    if (File::exists($this->versionPath)) {
        File::delete($this->versionPath);
    }

    $checker = new UpdateChecker();

    expect($checker->getCurrentVersion())->toBe('Development');
});

test('it returns Development when version file is empty', function () {
    File::put($this->versionPath, '   ');

    $checker = new UpdateChecker();

    expect($checker->getCurrentVersion())->toBe('Development');
});

test('it returns trimmed version from version file', function () {
    File::put($this->versionPath, " 1.2.3 \n");

    $checker = new UpdateChecker();

    expect($checker->getCurrentVersion())->toBe('1.2.3');
});
