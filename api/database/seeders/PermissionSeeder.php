<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Reset cached roles and permissions
		app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

		$collection = collect([
			'users' => ['viewAny', 'view', 'create', 'update', 'delete', 'changeStatus', 'resetPassword'],
			'roles' => ['viewAny', 'view', 'create', 'update', 'delete'],
		]);

		$collection->each(function ($item, $index) {
			if (Str::contains($index, '|singular')) {
				$singular = $index = Str::replace('|singular', '', $index);
			} else {
				$singular = Str::singular($index);
			}
			foreach ($item as $subItem) {
				Permission::updateOrCreate(['group' => $index, 'guard_name' => config('auth.defaults.guard'), 'name' => "$subItem " . $singular]);
			}
		});

		$role = Role::updateOrCreate(
			[
				'name' => Role::ROLE_SUPER_ADMIN,
			]
		);

		$role->givePermissionTo(Permission::all());

		if (app()->environment() != 'testing') {
			$user = User::firstOrCreate(['id' => 1], ['email' => 'info@info.com', 'name' => 'Super Admin', 'username' => 'admin', 'mobile' => '+970567940999', 'image' => "https://api.multiavatar.com/yassir.png", 'password' => bcrypt(123123)]);
			$user->assignRole($role);
		}
	}
}
