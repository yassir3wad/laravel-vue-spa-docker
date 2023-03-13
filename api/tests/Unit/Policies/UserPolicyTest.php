<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->seedPermissions();
	}

	public function test_user_with_all_permissions_can_do_all_actions()
	{
		$admin = $this->superUser();
		$user = User::factory()->create();

		$policy = new UserPolicy();

		$this->assertTrue($policy->viewAny($admin));
		$this->assertTrue($policy->view($admin, $user));
		$this->assertTrue($policy->create($admin));
		$this->assertTrue($policy->update($admin, $user));
		$this->assertTrue($policy->delete($admin, $user));
		$this->assertTrue($policy->changeStatus($admin, $user));
		$this->assertTrue($policy->resetPassword($admin, $user));
	}

	public function test_super_admin_user_cant_manipulate_himself()
	{
		$admin = $this->superUser();

		$policy = new UserPolicy();

		$this->assertTrue($policy->viewAny($admin));
		$this->assertTrue($policy->view($admin, $admin));
		$this->assertTrue($policy->create($admin));
		$this->assertFalse($policy->update($admin, $admin));
		$this->assertFalse($policy->delete($admin, $admin));
		$this->assertFalse($policy->changeStatus($admin, $admin));
		$this->assertFalse($policy->resetPassword($admin, $admin));
	}

	public function test_super_admin_user_cant_be_manipulated_by_others()
	{
		$admin = $this->superUser();
		$admin2 = $this->superUser();

		$policy = new UserPolicy();

		$this->assertTrue($policy->viewAny($admin2));
		$this->assertTrue($policy->view($admin2, $admin));
		$this->assertTrue($policy->create($admin2));
		$this->assertFalse($policy->update($admin2, $admin));
		$this->assertFalse($policy->delete($admin2, $admin));
		$this->assertFalse($policy->changeStatus($admin2, $admin));
		$this->assertFalse($policy->resetPassword($admin2, $admin));
	}

	public function test_user_cant_view_an_user_if_doesnt_have_view_permission()
	{
		$user = User::factory()->create();

		$policy = new UserPolicy();

		$this->assertFalse($policy->viewAny($user));
		$this->assertFalse($policy->view($user, $user));
	}

	public function test_user_cant_create_a_new_user_if_doesnt_have_create_permission()
	{
		$user = User::factory()->create();

		$policy = new UserPolicy();

		$this->assertFalse($policy->create($user));
	}

	public function test_user_cant_update_an_user_if_doesnt_have_update_permission()
	{
		$users = User::factory(2)->create();

		$policy = new UserPolicy();

		$this->assertFalse($policy->update($users->first(), $users->last()));
	}

	public function test_user_cant_delete_an_user_if_doesnt_have_delete_permission()
	{
		$users = User::factory(2)->create();

		$policy = new UserPolicy();

		$this->assertFalse($policy->delete($users->first(), $users->last()));
	}

	public function test_user_cant_change_status_of_an_user_if_doesnt_have_change_status_permission()
	{
		$users = User::factory(2)->create();

		$policy = new UserPolicy();

		$this->assertFalse($policy->changeStatus($users->first(), $users->last()));
	}

	public function test_user_cant_reset_password_of_an_user_if_doesnt_have_reset_password_permission()
	{
		$users = User::factory(2)->create();

		$policy = new UserPolicy();

		$this->assertFalse($policy->resetPassword($users->first(), $users->last()));
	}
}