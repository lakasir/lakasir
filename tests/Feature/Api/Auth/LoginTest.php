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
        $response = $this->postJson(route('api.auth.login'), $this->data());

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'payload' => ['token']]);
    }

    public function test_login_api_client_error()
    {
        $response = $this->postJson(route('api.auth.login'), array_merge($this->data(), [ 'password' => '' ]));

        $response->assertStatus(422)
                 ->assertJsonStructure(['success', 'errors']);
    }

    public function test_login_api_password_missmatch()
    {
        $response = $this->postJson(route('api.auth.login'), array_merge($this->data(), ['password' => 'dhfids[p]']));

        $response->assertStatus(422)
                 ->assertJsonStructure(['success', 'message']);
    }

    public function test_login_api_user_not_found()
    {
        $response = $this->postJson(route('api.auth.login'), array_merge($this->data(), ['email' => 'fasd@mail.com']));

        $response->assertStatus(422)
                 ->assertJsonStructure(['success', 'message']);
    }

    protected function data()
    {
        $user = User::first();
        $data = ['email' => $user->email, 'password' => '12345678'];

        return $data;
    }
}
