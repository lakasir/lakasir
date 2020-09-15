<?php

namespace Tests\Feature\Api\Auth;

use App\Traits\SetClietnCredentials;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use SetClietnCredentials;

    public function test_get_profile_success(): void
    {
        $this->setClientCredentialsToken();

        $response = $this->get(route('api.profile.index'), $this->oauth_headers);
        $response->assertStatus(200)
                 ->assertJsonStructure(
                     ['success', 'payload' => [
                         'id',
                         'email',
                         'username',
                         'address',
                         'bio',
                         'lang',
                         'image'
                     ]]
                 );
    }

    public function test_error_create_api_profile(): void
    {
        $this->setClientCredentialsToken();

        $response = $this->postJson(route('api.profile.store'), array_merge($this->data(), ['phone' => '']), $this->oauth_headers);

        $response->assertStatus(422);
    }

    public function test_success_create_api_profile(): void
    {
        $this->setClientCredentialsToken();

        $response = $this->postJson(route('api.profile.store'), $this->data(), $this->oauth_headers);

        $response->assertStatus(200);
    }

    protected function data()
    {
        return [
            'phone' => '0896739213',
            'address' => 'Jl Kalinyamatan',
            'photo_profile' => UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10)
        ];
    }


}
