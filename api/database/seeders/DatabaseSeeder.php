<?php

namespace Database\Seeders;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		(new PermissionSeeder)->run();

		$users = \App\Models\User::factory(100)->create();

		User::whereKey($users->random(50)->modelKeys())->update(['status' => ActiveStatusEnum::INACTIVE]);
	}
}
