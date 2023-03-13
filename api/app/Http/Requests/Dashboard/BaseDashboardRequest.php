<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseDashboardRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	protected function mapAttributeToArray($attr)
	{
		$attr = (array)$attr;
		$data = [];

		foreach ($attr as $t) {
			$data[$t] = $this->$t ? json_decode($this->$t, true) : null;
		}

		$this->merge($data);
	}

	protected function getPaginationRules(array $allowedSortColumns)
	{
		return [
			'page' => ['nullable', 'integer', 'min:1'],
			'per_page' => ['nullable', 'integer', 'min:1'],
			'sort' => ['nullable', 'string', Rule::in($allowedSortColumns)],
			'sort_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
		];
	}
}