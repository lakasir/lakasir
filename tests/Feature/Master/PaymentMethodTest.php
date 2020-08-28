<?php

namespace Tests\Feature\Master;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    public function test_error_name_client_payment_method()
    {
        $routeCreate = route('payment_method.create');
        $user = User::find(1);
        $createForm = $this->actingAs($user)->get($routeCreate);
        $response = $this->actingAs($user)->post(
            route('payment_method.store'),
            array_merge($this->data(), ['name' => ''])
        );

        $createForm->assertViewIs('app.master.payment_methods.create');
        $response->assertSessionHasErrors();
        $response->assertStatus(302);
        $response->assertRedirect($routeCreate);
    }

    public function test_success_create_payment_method()
    {
        $routeIndex = route('payment_method.index');
        $routeCreate = route('payment_method.create');
        $user = User::find(1);
        $createForm = $this->actingAs($user)->get($routeCreate);
        $response = $this->actingAs($user)->post(
            route('payment_method.store'),
            $this->data()
        );

        $createForm->assertViewIs('app.master.payment_methods.create');
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect($routeIndex);
    }

    public function test_success_update_payment_method()
    {
        $routeIndex = route('payment_method.index');
        $routeCreate = route('payment_method.create');
        $user = User::find(1);
        $createForm = $this->actingAs($user)->get($routeCreate);
        $paymentMethd = PaymentMethod::latest()->first();
        $response = $this->actingAs($user)->put(
            route('payment_method.update', $paymentMethd),
            array_merge($this->data(), ['name' => 'OVO'])
        );

        $createForm->assertViewIs('app.master.payment_methods.create');
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect($routeIndex);
    }

    protected function data()
    {
        return [
            'name' => 'DANA',
            'code' => 'DANA12018',
            'visible_in' => [
                'purchasing' => true,
                'selling' => true
            ],
        ];
    }
}
