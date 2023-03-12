<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication, LazilyRefreshDatabase;

	protected function authUser()
	{
		$user = $this->superUser();
		$this->be($user);

		return $user;
	}

	protected function superUser()
	{
		$user = User::factory()->create();
		$user->assignRole(Role::ROLE_SUPER_ADMIN);
		return $user;
	}

	protected function seedPermissions()
	{
		Event::listen(MigrationsEnded::class, function () {
			$this->artisan('db:seed', ['--class' => PermissionSeeder::class]);
		});
	}
}
