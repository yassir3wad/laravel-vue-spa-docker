<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RoleService
{
	/**
	 * @var Role
	 */
	private Role $model;

	/**
	 * @param Role $model
	 */
	public function __construct(Role $model)
	{
		$this->model = $model;
	}

	/**
	 * @param array $sort
	 * @param array $pagination
	 * @param array $filters
	 *
	 * @return LengthAwarePaginator
	 */
	public function getRoles(array $sort = [], array $pagination = [], array $filters = []): LengthAwarePaginator
	{
		return $this->model
			->select(['id', 'name', 'created_at'])
			->orderBy($sort['column'] ?? 'id', $sort['direction'] ?? 'desc')
			->presentWhereLike('name', Arr::get($filters, 'name'))
			->presentWhereDate('created_at', '>=', Arr::get($filters, 'from_date'))
			->presentWhereDate('created_at', '<=', Arr::get($filters, 'to_date'))
			->withCount('permissions')
			->paginate(
				perPage: $pagination['per_page'] ?? config('app.per_page'), page: $pagination['page'] ?? 1
			);
	}

	/**
	 * @param array $attributes
	 *
	 * @return Role
	 * @throws \Throwable
	 */
	public function createRole(array $attributes): Role
	{
		try {
			DB::beginTransaction();

			$role = $this->model->newModelInstance(
				Arr::only($attributes, ['name'])
			);
			$role->save();

			$role->givePermissionTo($attributes['permissions']);

			DB::commit();
		} catch (\Exception $exception) {
			DB::rollBack();

			throw $exception;
		}

		return $role;
	}

	/**
	 * @param Role $role
	 * @param array $attributes
	 *
	 * @return void
	 * @throws \Throwable
	 */
	public function updateRole(Role $role, array $attributes): void
	{
		try {
			DB::beginTransaction();

			$role->update(
				Arr::only($attributes, ['name'])
			);

			$role->syncPermissions($attributes['permissions']);

			DB::commit();
		} catch (\Exception $exception) {
			DB::rollBack();

			throw $exception;
		}
	}

	/**
	 * @param Role $role
	 *
	 * @return void
	 * @throws \Throwable
	 */
	public function deleteRole(Role $role): void
	{
		try {
			DB::beginTransaction();

			$role->delete();

			DB::commit();
		} catch (\Exception $exception) {
			DB::rollBack();

			throw $exception;
		}
	}
}
