<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends TestCase
{
    public function test_success_create_user()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'testuser@mail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => 'employee'
        ];
        $user = User::find(1);
        $response = $this->actingAs($user)->post(route('user.store'), $data);

        $response->dumpSession();
        $response->assertStatus(302);
        $response->assertRedirect(route('user.index'));
    }

    public function test_success_update_user()
    {
        $data = [
            'username' => 'edit',
            'email' => 'edit@mail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => 'employee'
        ];
        $auth = User::find(1);
        $user = User::inRandomOrder()->take(1)->first();
        $response = $this->actingAs($auth)->put(route('user.update', $user), $data);

        $response->dumpSession();
        $response->assertStatus(302);
        $response->assertRedirect(route('user.index'));
    }
}
