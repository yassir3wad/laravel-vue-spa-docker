<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Responder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Role\SearchRoleRequest;
use App\Http\Requests\Dashboard\Role\UpsertRoleRequest;
use App\Http\Resources\Dashboard\Role\PermissionResource;
use App\Http\Resources\Dashboard\Role\RoleResource;
use App\Http\Resources\Dashboard\Role\SimpleRoleResource;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use App\Services\RoleService;
use Illuminate\Http\Response;

class RoleController extends Controller
{
	private RoleService $roleService;
	private PermissionRepository $permissionRepository;

	/**
	 * @param RoleService $roleService
	 */
	public function __construct(RoleService $roleService, PermissionRepository $permissionRepository)
	{
		$this->roleService = $roleService;
		$this->permissionRepository = $permissionRepository;

		$this->authorizeResource(Role::class);
	}

	/**
	 * @param SearchRoleRequest $request
	 *
	 * @return Response
	 */
	public function index(SearchRoleRequest $request)
	{
		$roles = $this->roleService->getRoles(
			[
				'column' => $request->sort,
				'direction' => $request->sort_dir,
			],
			$request->only(['page', 'per_page']),
			$request->only(['name', 'from_date', 'to_date']),
		);

		return Responder::setPaginatedData(
			RoleResource::collection($roles)
		)->respond();
	}

	/**
	 * @param Role $role
	 *
	 * @return Response
	 */
	public function show(Role $role)
	{
		$role->load('permissions');

		return Responder::setData(
			new RoleResource($role)
		)->respond();
	}

	/**
	 * @param UpsertRoleRequest $request
	 *
	 * @return Response
	 * @throws \InvalidArgumentException
	 */
	public function store(UpsertRoleRequest $request)
	{
		$role = $this->roleService->createRole(
			$request->only(['name', 'permissions'])
		);

		return Responder::setData(new RoleResource($role))
			->setMessage(trans('dashboard.created_successfully'))
			->setStatusCode(Response::HTTP_CREATED)
			->respond();
	}

	/**
	 * @param Role $role
	 * @param UpsertRoleRequest $request
	 *
	 * @return Response
	 * @throws \InvalidArgumentException
	 */
	public function update(Role $role, UpsertRoleRequest $request)
	{
		$this->roleService->updateRole(
			$role,
			$request->only(['name', 'permissions'])
		);

		return Responder::setData(new RoleResource($role))
			->setMessage(trans('dashboard.updated_successfully'))
			->respond();
	}

	/**
	 * @param Role $role
	 *
	 * @return Response
	 */
	public function destroy(Role $role)
	{
		$this->roleService->deleteRole($role);

		return Responder::setMessage(trans('dashboard.deleted_successfully'))
			->respond();
	}

	/**
	 * @return void
	 */
	public function list()
	{
		$this->authorize('viewAny', Role::class);

		return Responder::setData(
			SimpleRoleResource::collection($this->permissionRepository->getAllRoles())
		)->respond();
	}

	/**
	 *
	 * @return Response
	 */
	public function permissions()
	{
		$this->authorize('viewAny', Role::class);

		return Responder::setData(
			PermissionResource::collection($this->permissionRepository->getAllPermissions())
		)->respond();
	}
}
