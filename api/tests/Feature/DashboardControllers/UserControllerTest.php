<?php

namespace Tests\Feature\DashboardControllers;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->seedPermissions();
	}

	/**
	 * A basic test example.
	 */
	public function test_admin_can_get_all_users_list(): void
	{
		$this->authUser();
		User::factory(3)->create();

		$response = $this->getJson(route('users.index'));

		$response->assertSuccessful();
		$response->assertJsonCount(4, 'data');
		$response->assertJsonStructure([
			'data' => [
				[
					'id',
					'name',
					'username',
					'email',
					'image',
					'status',
					'created_at'
				]
			],
			'meta' => [
				'current_page',
				'from',
				'last_page',
				'path',
				'per_page',
				'to',
				'total'
			]
		]);
	}

	public function test_admin_can_create_a_new_user()
	{
		$this->authUser();
		$data = [
			'name' => 'yassir awad',
			'username' => 'yassirawad',
			'email' => 'com.eng.yassir@gmail.com',
			'mobile' => '+970567940999',
			'password' => 'test123',
			'password_confirmation' => 'test123',
			'image' => UploadedFile::fake()->image('test.png'),
			'status' => ActiveStatusEnum::ACTIVE->value,
		];

		$response = $this->postJson(route('users.store'), $data);

		$response->assertSuccessful();
		$response->assertJsonStructure([
			'data' => [
				'id',
				'name',
				'username',
				'email',
				'mobile',
				'image',
				'status',
				'created_at'
			]
		]);
		$this->assertEquals($data['name'], $response->json('data.name'));
		$this->assertEquals($data['username'], $response->json('data.username'));
		$this->assertEquals($data['email'], $response->json('data.email'));
		$this->assertEquals($data['mobile'], $response->json('data.mobile'));
		$this->assertNotNull($response->json('data.image'));
	}

	public function test_cant_create_a_user_if_the_name_is_missing()
	{
		$this->authUser();
		$data = [
			'username' => 'yassirawad',
			'email' => 'com.eng.yassir@gmail.com',
			'mobile' => '+970567940999',
			'password' => 'test123',
			'password_confirmation' => 'test123',
			'image' => UploadedFile::fake()->image('test.png'),
			'status' => ActiveStatusEnum::ACTIVE->value,
		];

		$response = $this->postJson(route('users.store'), $data);

		$response->assertStatus(422);
		$response->assertJsonValidationErrorFor('name');
	}

	public function test_cant_create_a_user_if_the_email_is_missing()
	{
		$this->authUser();
		$data = [
			'name' => 'yassir awad',
			'username' => 'yassirawad',
			'mobile' => '+970567940999',
			'password' => 'test123',
			'password_confirmation' => 'test123',
			'image' => UploadedFile::fake()->image('test.png'),
			'status' => ActiveStatusEnum::ACTIVE->value,
		];

		$response = $this->postJson(route('users.store'), $data);

		$response->assertStatus(422);
		$response->assertJsonValidationErrorFor('email');
	}

	public function test_cant_create_a_user_if_the_email_is_used()
	{
		$user = $this->authUser();

		$data = [
			'name' => 'yassir awad',
			'username' => 'yassirawad',
			'email' => $user->email,
			'mobile' => '+970567940999',
			'password' => 'test123',
			'password_confirmation' => 'test123',
			'image' => UploadedFile::fake()->image('test.png'),
			'status' => ActiveStatusEnum::ACTIVE->value,
		];

		$response = $this->postJson(route('users.store'), $data);

		$response->assertStatus(422);
		$response->assertJsonValidationErrorFor('email');
	}

	public function test_admin_can_update_a_user()
	{
		$this->authUser();
		$user = User::factory()->create();
		$data = [
			'name' => 'yassir awad',
			'username' => 'yassirawad',
			'email' => 'com.eng.yassir@gmail.com',
			'mobile' => '+970567940999',
			'status' => ActiveStatusEnum::INACTIVE->value,
		];

		$response = $this->putJson(route('users.update', ['user' => $user]), $data);

		$response->assertSuccessful();
		$response->assertJsonStructure([
			'data' => [
				'id',
				'name',
				'username',
				'email',
				'mobile',
				'image',
				'status',
				'created_at'
			]
		]);
		$this->assertEquals($data['name'], $response->json('data.name'));
		$this->assertEquals($data['username'], $response->json('data.username'));
		$this->assertEquals($data['email'], $response->json('data.email'));
		$this->assertEquals($data['mobile'], $response->json('data.mobile'));
		$this->assertEquals($data['status'], $response->json('data.status'));
		$this->assertEquals($user->image, $response->json('data.image'));
	}

	public function test_admin_can_delete_a_user()
	{
		$this->authUser();
		$user = User::factory()->create();

		$response = $this->deleteJson(route('users.update', ['user' => $user]));

		$response->assertSuccessful();
		$this->expectException(ModelNotFoundException::class);
		$user->refresh();
	}

	public function test_admin_can_reset_user_password()
	{
		$this->authUser();
		$user = User::factory()->create();
		$newPassword = 'test123321';

		$response = $this->patchJson(route('users.reset-password', ['user' => $user]), [
			'password' => $newPassword,
			'password_confirmation' => $newPassword
		]);

		$response->assertSuccessful();
		$this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
	}

	public function test_admin_can_update_user_status()
	{
		$this->authUser();
		$user = User::factory()->create();

		$response = $this->patchJson(route('users.change-status', ['user' => $user]), [
			'status' => ActiveStatusEnum::INACTIVE
		]);

		$response->assertSuccessful();
		$this->assertEquals(ActiveStatusEnum::INACTIVE, $user->fresh()->status);
	}
}