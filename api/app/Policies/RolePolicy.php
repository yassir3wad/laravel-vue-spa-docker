<?php

namespace App\Policies;

use App\Models\Role;
use Illuminate\Contracts\Auth\Access\Authorizable;

class RolePolicy
{
	public function viewAny(Authorizable $user)
	{
		return $user->can('viewAny role');
	}

	public function view(Authorizable $user, Role $model)
	{
		return $user->can('view role');
	}

	public function create(Authorizable $user)
	{
		return $user->can('create role');
	}

	public function update(Authorizable $user, Role $model)
	{
		return $user->can('update role') && $model->getKey() !== 1;
	}

	public function delete(Authorizable $user, Role $model)
	{
		return $user->can('delete role') && $model->getKey() !== 1;
	}

	public function changeStatus(Authorizable $user, Role $model)
	{
		return $user->can('changeStatus role') && $model->getKey() !== 1;
	}
}
