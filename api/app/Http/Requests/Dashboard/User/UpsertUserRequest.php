<?php

namespace App\Http\Requests\Dashboard\User;

use App\Enums\ActiveStatusEnum;
use App\Http\Requests\Dashboard\BaseDashboardRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UpsertUserRequest extends BaseDashboardRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'alpha_num', 'max:255', Rule::unique(User::class)->ignore($this->user?->getKey())],
			'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user?->getKey())],
			'mobile' => ['required', 'phone:INTERNATIONAL'],
			'status' => ['required', 'string', Rule::in(ActiveStatusEnum::values())],
			'password' => ['nullable', Rule::when(!$this->user, ['required']), 'string', Password::min(6)->letters()->numbers(), 'confirmed'],
			'image' => ['nullable', Rule::when(!$this->user, ['required']), 'file', File::types(config('upload.types.users.mimetypes'))->max(config('upload.types.users.max_size'))],

			'role_ids' => ['nullable', 'array'],
			'role_ids.*' => ['required', 'integer', Rule::exists(Role::class, 'id')],
		];
	}

	protected function prepareForValidation()
	{
		$this->mapAttributeToArray(['role_ids']);
	}
}
