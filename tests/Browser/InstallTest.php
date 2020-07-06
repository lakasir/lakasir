<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InstallTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_show_install_page_database()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/install?tab=database')
                    ->type('host', 'localhost')
                    ->type('name', 'lakasir')
                    ->type('username', 'root')
                    ->type('password', '`');
            $browser->press('Next')
                    ->assertPathIs('/install');
        });
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_show_install_page_user()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/install?tab=user')
                    ->type('username', 'admin')
                    ->type('email', 'admin@example.com')
                    ->type('password', '12345678')
                    ->type('password_confirmation', '12345678');
            $browser->press('Next')
                    ->assertPathIs('/install');
        });
    }
}
