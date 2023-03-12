<?php

namespace App\Services;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class UserService
{
	/**
	 * @var User
	 */
	private User $model;

	private FileUploaderService $uploaderService;

	/**
	 * @var Hasher
	 */
	private Hasher $hasher;

	/**
	 * @param User $model
	 * @param FileUploaderService $uploaderService
	 * @param Hasher $hasher
	 */
	public function __construct(User $model, FileUploaderService $uploaderService, Hasher $hasher)
	{
		$this->uploaderService = $uploaderService;
		$this->model = $model;
		$this->hasher = $hasher;
	}

	/**
	 * @param array $sort
	 * @param array $pagination
	 * @param array $filters
	 *
	 * @return LengthAwarePaginator
	 */
	public function getUsers(array $sort = [], array $pagination = [], array $filters = []): LengthAwarePaginator
	{
		return $this->model
			->select(['id', 'name', 'email', 'mobile', 'username', 'image', 'status', 'created_at'])
			->orderBy($sort['column'] ?? 'id', $sort['direction'] ?? 'desc')
			->presentWhereLike('name', Arr::get($filters, 'name'))
			->presentWhereLike('email', Arr::get($filters, 'email'))
			->presentWhereDate('created_at', '>=', Arr::get($filters, 'from_date'))
			->presentWhereDate('created_at', '<=', Arr::get($filters, 'to_date'))
			->paginate(
				perPage: $pagination['per_page'] ?? config('app.per_page'), page: $pagination['page'] ?? 1
			);
	}

	/**
	 * @param array $attributes
	 *
	 * @return
	 * @throws \InvalidArgumentException
	 */
	public function createUser(array $attributes): User
	{
		if (!$attributes['image'] instanceof UploadedFile) {
			throw new \InvalidArgumentException('invalid value for image');
		}

		$attributes['image'] = $this->uploaderService->uploadFile($attributes['image'], config('upload.types.users.folder'));

		$user = $this->model->newModelInstance(
			Arr::only($attributes, ['name', 'username', 'email', 'mobile', 'image', 'status'])
		)
			->fill(['password' => $this->hasher->make($attributes['password'])]);

		$user->save();

		return $user;
	}

	/**
	 * @param User $user
	 * @param array $attributes
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function updateUser(User $user, array $attributes): void
	{
		if (isset($attributes['image'])) {
			if (!$attributes['image'] instanceof UploadedFile) {
				throw new \InvalidArgumentException('invalid value for image');
			}

			$attributes['image'] = $this->uploaderService->uploadFile($attributes['image'], config('upload.types.users.folder'));
		}

		$user->update(
			Arr::only($attributes, ['name', 'username', 'email', 'mobile', 'image', 'status'])
		);
	}

	/**
	 * @param User $user
	 *
	 * @return void
	 */
	public function deleteUser(User $user): void
	{
		$user->delete();
	}

	/**
	 * @param User $user
	 * @param string $newPassword
	 *
	 * @return void
	 */
	public function updateUserPassword(User $user, string $newPassword): void
	{
		$user->update(['password' => $this->hasher->make($newPassword)]);
	}

	/**
	 * @param User $user
	 * @param ActiveStatusEnum $newStatusEnum
	 *
	 * @return void
	 */
	public function changeUserStatus(User $user, ActiveStatusEnum $newStatusEnum): void
	{
		$user->update(['status' => $newStatusEnum]);
	}
}
