<?php

namespace App\Http\Requests\Dashboard\User;

use App\Http\Requests\Dashboard\BaseDashboardRequest;

class SearchUserRequest extends BaseDashboardRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			...$this->getPaginationRules(allowedSortColumns: ['id', 'name', 'email', 'created_at']),

			'name' => ['nullable', 'string', 'max:255'],
			'email' => ['nullable', 'string', 'max:255'],
			'from_date' => ['nullable', 'date', 'date_format:Y-m-d'],
			'to_date' => ['nullable', 'date', 'date_format:Y-m-d'],
			'role_id' => ['nullable', 'integer'],
		];
	}
}
