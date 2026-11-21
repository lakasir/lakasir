<?php

use App\Models\Tenants\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\post;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Storage::fake(config('filesystems.tmp_disk'));
});

test('an image is stored on the tmp disk and described back', function () {
    $response = actingAs(User::first())
        ->post('/api/temp/upload', ['file' => UploadedFile::fake()->image('produk.png')])
        ->assertOk();

    $name = $response->json('data.name');
    expect($response->json('data.original_name'))->toBe('produk.png')
        ->and($response->json('data.url'))->toContain($name);

    Storage::disk(config('filesystems.tmp_disk'))->assertExists($name);
});

test('a file that is not an image is rejected', function (string $name) {
    actingAs(User::first())
        ->postJson('/api/temp/upload', ['file' => UploadedFile::fake()->create($name, 4)])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('file');
})->with(['shell.php', 'page.html', 'vector.svg']);

test('an oversized image is rejected', function () {
    actingAs(User::first())
        ->postJson('/api/temp/upload', ['file' => UploadedFile::fake()->image('besar.png')->size(9000)])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('file');
});

test('a request without a file is a 422, not a 500', function () {
    actingAs(User::first())
        ->postJson('/api/temp/upload')
        ->assertUnprocessable()
        ->assertJsonValidationErrors('file');
});

test('uploads need a signed-in user', function () {
    postJson('/api/temp/upload', ['file' => UploadedFile::fake()->image('a.png')])
        ->assertUnauthorized();
});
