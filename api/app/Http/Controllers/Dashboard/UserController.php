<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ActiveStatusEnum;
use App\Facades\Responder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\ResetPasswordRequest;
use App\Http\Requests\Dashboard\User\UpsertUserRequest;
use App\Http\Requests\Dashboard\User\SearchUserRequest;
use App\Http\Resources\Dashboard\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
	/**
	 * @var UserService
	 */
	private UserService $userService;

	/**
	 * @param UserService $userService
	 */
	public function __construct(UserService $userService)
	{
		$this->userService = $userService;

		$this->authorizeResource(User::class);
	}

	/**
	 * @param SearchUserRequest $request
	 *
	 * @return Response
	 */
	public function index(SearchUserRequest $request)
	{
		$users = $this->userService->getUsers(
			[
				'column' => $request->sort,
				'direction' => $request->sort_dir,
			],
			$request->only(['page', 'per_page']),
			$request->only(['name', 'email', 'from_date', 'to_date', 'role_id']),
		);

		return Responder::setPaginatedData(
			UserResource::collection($users)
		)->respond();
	}

	/**
	 * @param User $user
	 *
	 * @return Response
	 */
	public function show(User $user)
	{
		$user->load('roles');

		return Responder::setData(
			new UserResource($user)
		)->respond();
	}

	/**
	 * @param UpsertUserRequest $request
	 *
	 * @return Response
	 * @throws \InvalidArgumentException
	 */
	public function store(UpsertUserRequest $request)
	{
		$user = $this->userService->createUser(
			$request->only(['name', 'email', 'username', 'mobile', 'image', 'status', 'password', 'password_confirmation', 'role_ids'])
		);

		return Responder::setData(new UserResource($user))
			->setMessage(trans('dashboard.created_successfully'))
			->setStatusCode(Response::HTTP_CREATED)
			->respond();
	}

	/**
	 * @param User $user
	 * @param UpsertUserRequest $request
	 *
	 * @return Response
	 * @throws \InvalidArgumentException
	 */
	public function update(User $user, UpsertUserRequest $request)
	{
		$this->userService->updateUser(
			$user,
			$request->only(['name', 'email', 'username', 'mobile', 'image', 'status', 'role_ids'])
		);

		return Responder::setData(new UserResource($user))
			->setMessage(trans('dashboard.updated_successfully'))
			->respond();
	}

	/**
	 * @param User $user
	 *
	 * @return Response
	 */
	public function destroy(User $user)
	{
		$this->userService->deleteUser($user);

		return Responder::setMessage(trans('dashboard.deleted_successfully'))
			->respond();
	}

	/**
	 * @param User $user
	 * @param ResetPasswordRequest $request
	 *
	 * @return mixed
	 * @throws AuthorizationException
	 */
	public function resetPassword(User $user, ResetPasswordRequest $request)
	{
		$this->authorize('resetPassword', $user);

		$this->userService->updateUserPassword($user, $request->password);

		return Responder::setMessage(trans('dashboard.updated_successfully'))
			->respond();
	}

	/**
	 * @return Response
	 *
	 * @throws AuthorizationException
	 * @throws ValidationException
	 */
	public function changeStatus(User $user, Request $request)
	{
		$this->authorize('changeStatus', $user);

		$request->validate(['status' => ['required', 'string', Rule::in(ActiveStatusEnum::values())]]);

		$this->userService->changeUserStatus($user, ActiveStatusEnum::from($request->status));

		return Responder::setMessage(trans('dashboard.updated_successfully'))
			->respond();
	}
}
