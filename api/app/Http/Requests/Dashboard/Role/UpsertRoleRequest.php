<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Http\Requests\Dashboard\BaseDashboardRequest;
use App\Models\Permission;
use Illuminate\Validation\Rule;

class UpsertRoleRequest extends BaseDashboardRequest
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
			'permissions' => ['required', 'array'],
			'permissions.*' => ['required', 'integer', Rule::exists(Permission::class, 'id')],
		];
	}
}
