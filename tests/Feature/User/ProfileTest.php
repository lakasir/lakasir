<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_success_create_profile(): void
    {
        $data = [
            'photo_profile' => UploadedFile::fake()->image('avatar.jpg', 500, 200)->size(10),
            'phone' => rand(0000000000, 9999999999),
            'bio' => 'lorem',
            'address' => 'address'
        ];
        $user = User::find(1);
        $response = $this->actingAs($user)->post(route('profile.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('profile.index'));
    }
}
