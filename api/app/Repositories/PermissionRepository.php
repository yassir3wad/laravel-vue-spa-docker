<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Models\Role;

class PermissionRepository
{
	private Role $role;
	private Permission $permission;

	public function __construct(Role $role, Permission $permission)
	{
		$this->role = $role;
		$this->permission = $permission;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAllRoles()
	{
		return $this->role::query()->oldest()->get();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAllPermissions()
	{
		return $this->permission::query()->oldest()->get();
	}
}