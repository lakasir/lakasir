<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use InvalidArgumentException;
use SebastianBergmann\RecursionContext\InvalidArgumentException as RecursionContextInvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use Spatie\Permission\Models\Permission;
use Tests\CreatesApplication;

/**
 * Class FeatureTestCase
 * @author sheenazien8
 */
abstract class FeatureTestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var Generator */
    protected $faker;

    protected $user;

    /**
     * @return void
     * @throws BindingResolutionException
     * @throws RoleAlreadyExists
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        $user = factory(User::class)->create([
            'username' => 'ownertest',
            'email' => 'ownertest@mail.com'
        ]);
        Role::create(['name' => 'owner']);
        $user->assignRole('owner');
        $user->assignRole('owner');
        $this->faker = Faker::create();
        $this->user = $user;

        // Disable debugbar
        config(['debugbar.enabled', false]);
    }
    /**
     * Empty for default user.
     *
     * @param User|null $user
     * @return FeatureTestCase
     */
    protected function loginAs(User $user = null)
    {
        if (!$user) {
            $user = $this->user;
        }

        return $this->actingAs($user);
    }

    /**
     * @param mixed $permission_name
     * @return void
     */
    protected function assignPermission($permission_name)
    {
        $permission = Permission::create(['name' => $permission_name]);
        $this->user->roles->first()->givePermissionTo($permission);
    }

    /** @return array  */
    protected function ajaxHeader(): array
    {
        return [
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ];
    }

    /**
     * @param string $level
     * @param string $message
     * @return void
     * @throws BindingResolutionException
     * @throws RecursionContextInvalidArgumentException
     * @throws ExpectationFailedException
     */
    protected function assertFlashLevel(string $level, string $message = '')
    {
        $flash['level'] = null;
        if ($flash_message = session('laravel_flash_message')) {
            $flash = $flash_message;
        }
        $this->assertEquals($level, $flash['level']);
        if (!empty($message)) {
            $this->assertEquals($message, $flash['message']);
        }
    }
}
