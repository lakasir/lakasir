<?php

use App\Livewire\PriceSetting;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(PriceSetting::class)
        ->assertStatus(200);
});
