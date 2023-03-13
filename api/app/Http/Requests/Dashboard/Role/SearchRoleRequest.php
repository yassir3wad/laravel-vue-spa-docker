<?php

namespace App\Http\Requests\Dashboard\Role;

use App\Http\Requests\Dashboard\BaseDashboardRequest;

class SearchRoleRequest extends BaseDashboardRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			...$this->getPaginationRules(allowedSortColumns: ['id', 'name', 'created_at']),

			'name' => ['nullable', 'string', 'max:255'],
			'from_date' => ['nullable', 'date', 'date_format:Y-m-d'],
			'to_date' => ['nullable', 'date', 'date_format:Y-m-d'],
		];
	}
}
