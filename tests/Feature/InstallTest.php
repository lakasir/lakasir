<?php

namespace Tests\Feature;

use App\Jobs\UpdateEnv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InstallTest extends TestCase
{
    public function test_install_database()
    {
        $response = $this->post(route('install.databaseStore'), [
            'host' => getenv('DB_HOST', 'localhost'),
            'name' => getenv('DB_DATABASE', 'lakasir'),
            'username' => getenv('DB_USERNAME', 'root'),
            'password' => getenv('DB_PASSWORD', '')
        ]);
        $redirect = '/install?tab=user';
        if (getenv('INSTALL') != "false") {
            $redirect = '';
        }

        $response->assertStatus(302);
        $response->assertRedirect($redirect);
    }

    public function test_install_user()
    {
        $response = $this->post(route('install.userStore'), [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);
        $redirect = '/install?tab=company';
        if (getenv('INSTALL') != "false") {
            $redirect = '';
        }

        $response->assertStatus(302);
        $response->assertRedirect($redirect);
    }

    public function test_install_company()
    {
        $array_options = config('array_options.business_type');
        $response = $this->withSession([
            'user' => [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => '12345678'
            ]
        ])->post(route('install.companyStore'), [
            'business_type' => $array_options[rand(0, count($array_options) - 1)],
            'business_description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr,sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.'
        ]);

        /**
         * FIXME: update the value getenv install variable <sheenazien 2020-07-01>
         * the first value don't want to change
         * but overwritten and become one word
         */

        if (app()->environment() == 'production') {
            UpdateEnv::dispatchNow([
                'INSTALL=' => 'false'
            ]);
        }

        $redirect = '/completed';
        if (getenv('INSTALL') != "false") {
            $redirect = '/';
        }

        $response->assertStatus(302);
        $response->assertRedirect($redirect);
    }
}
