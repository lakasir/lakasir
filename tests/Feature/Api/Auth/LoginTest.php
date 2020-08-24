<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_api_success()
    {
        $user = User::inRandomOrder()->take(1)->first();
        $data = ['email' => $user->email, 'password' => '12345678'];
        $response = $this->post(route('api.auth.login'), $data);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'payload' => ['token']]);
    }

    public function test_login_api_client_error()
    {
        $user = User::inRandomOrder()->take(1)->first();
        $data = ['email' => $user->email];
        $response = $this->post(route('api.auth.login'), $data);

        $response->assertStatus(422)
                 ->assertJsonStructure(['success', 'errors']);
    }

    public function test_login_api_password_missmatch()
    {
        $user = User::inRandomOrder()->take(1)->first();
        $data = ['email' => $user->email, 'password' => '23979843'];
        $response = $this->post(route('api.auth.login'), $data);

        $response->assertStatus(422)
                 ->assertJsonStructure(['success', 'message']);
    }

    public function test_login_api_user_not_found()
    {
        $data = ['email' => 'admin21213@example.com', 'password' => '23979843'];
        $response = $this->post(route('api.auth.login'), $data);

        $response->assertStatus(422)
                 ->assertJsonStructure(['success', 'message']);
    }
}
