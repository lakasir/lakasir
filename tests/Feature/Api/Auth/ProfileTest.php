<?php

namespace Tests\Feature\Api\Auth;

use App\Traits\SetClietnCredentials;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use SetClietnCredentials;

    public function test_get_profile_success(): void
    {
        $this->setClientCredentialsToken();

        $response = $this->get(route('api.auth.profile'), $this->oauth_headers);
        $response->assertStatus(200)
                 ->assertJsonStructure(
                     ['success', 'payload' => [
                         'id',
                         'email',
                         'email_verified_at',
                         'username',
                         'profile',
                         'created_at',
                         'updated_at',
                     ]]
                 );
    }
}
