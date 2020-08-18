<?php

namespace Tests\Feature\Transaction;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CashDrawerTest extends TestCase
{
    public function test_success_open_cashdrawer()
    {
        $data = ['money' => 500000];
        $user = User::inRandomOrder()->first();
        $response = $this->actingAs($user)->post(route('cashdrawer.open'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('selling.index'));
    }

    public function test_success_close_cashdrawer()
    {
        $data = ['money' => 500000];
        $user = User::inRandomOrder()->first();
        $response = $this->actingAs($user)->post(route('cashdrawer.close'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('selling.index'));
    }
}
