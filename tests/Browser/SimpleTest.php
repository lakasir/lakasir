<?php

use Laravel\Dusk\Browser;

test('simple dusk test', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSee('Laravel');
    });
});