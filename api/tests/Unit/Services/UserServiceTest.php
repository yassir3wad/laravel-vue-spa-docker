<?php

namespace Tests\Unit\Services;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
	public function test_can_get_paginated_users_list()
	{
		User::factory(4)->create();

		$result = app(UserService::class)->getUsers(pagination: ['per_page' => 2]);

		$this->assertInstanceOf(LengthAwarePaginator::class, $result);
		$this->assertCount(2, $result);
		$this->assertEquals(1, $result->currentPage());
		$this->assertEquals(2, $result->lastPage());
		$this->assertEquals(2, $result->perPage());
	}

	public function test_can_get_paginated_users_list_with_sorting()
	{
		User::factory(3)->sequence(['name' => 'x'], ['name' => 'a'], ['name' => 'c'])->create();

		$result = app(UserService::class)->getUsers(sort: ['column' => 'name', 'direction' => 'asc']);
		$this->assertEquals('a', $result->value('name'));
	}

	public function test_can_get_paginated_users_list_with_filters()
	{
		User::factory(3)->sequence(['name' => 'foo'], ['name' => 'bar'], ['name' => 'test'])->create();

		$result = app(UserService::class)->getUsers(filters: ['name' => 'oo']);

		$this->assertCount(1, $result);
		$this->assertEquals('foo', $result->value('name'));
	}

	public function test_cant_get_paginated_users_list_if_sort_direction_is_invalid()
	{
		$this->expectException(\InvalidArgumentException::class);

		app(UserService::class)->getUsers(sort: ['direction' => 'xyz']);
	}

	public function test_can_create_a_new_user()
	{
		$data = [
			'name' => 'yassir awad',
			'username' => 'yassirawad',
			'email' => 'com.eng.yassir@gmail.com',
			'mobile' => '+970567940999',
			'password' => 'test123',
			'image' => UploadedFile::fake()->image('test.png'),
		];

		$user = app(UserService::class)->createUser($data);

		$this->assertEquals($data['name'], $user->name);
		$this->assertEquals($data['username'], $user->username);
		$this->assertEquals($data['email'], $user->email);
		$this->assertEquals($data['mobile'], $user->mobile);
		$this->assertNotNull($user->image);
		$this->assertTrue(Hash::check($data['password'], $user->password));
	}

	public function test_cant_create_a_new_user_if_image_is_invalid()
	{
		$data = [
			'image' => 'xyz',
		];

		$this->expectException(\InvalidArgumentException::class);

		app(UserService::class)->createUser($data);
	}

	public function test_can_update_user()
	{
		$user = User::factory()->create(['image' => fake()->imageUrl]);
		$data = [
			'name' => 'yassir awad',
			'username' => 'yassirawad',
			'email' => 'com.eng.yassir@gmail.com',
			'mobile' => '+970567940999',
			'password' => 'test123',
			'image' => UploadedFile::fake()->image('test.png'),
		];

		app(UserService::class)->updateUser($user, $data);

		$this->assertEquals($data['name'], $user->name);
		$this->assertEquals($data['username'], $user->username);
		$this->assertEquals($data['email'], $user->email);
		$this->assertEquals($data['mobile'], $user->mobile);
		$this->assertStringStartsWith('users/', $user->image);
	}

	public function test_cant_update_user_if_image_is_invalid()
	{
		$user = User::factory()->create();
		$data = [
			'image' => 'xyz',
		];

		$this->expectException(\InvalidArgumentException::class);

		app(UserService::class)->updateUser($user, $data);
	}

	public function test_can_delete_user()
	{
		$user = User::factory()->create();

		app(UserService::class)->deleteUser($user);

		$this->assertNull($user->fresh());
	}

	public function test_can_change_status()
	{
		$user = User::factory()->create();

		app(UserService::class)->changeUserStatus($user, ActiveStatusEnum::INACTIVE);

		$this->assertEquals(ActiveStatusEnum::INACTIVE, $user->status);
	}

	public function test_can_update_password()
	{
		$user = User::factory()->create();
		$newPassword = '123123';

		app(UserService::class)->updateUserPassword($user, $newPassword);

		$this->assertTrue(Hash::check($newPassword, $user->password));
	}
}