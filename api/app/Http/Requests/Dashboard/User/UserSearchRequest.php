<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserSearchRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
	 */
	public function rules(): array
	{
		return [
			'page' => ['nullable', 'integer', 'min:1'],
			'per_page' => ['nullable', 'integer', 'min:1'],
			'sort' => ['nullable', 'string', Rule::in(['id', 'name', 'email', 'created_at'])],
			'sort_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],

			'name' => ['nullable', 'string', 'max:255'],
			'email' => ['nullable', 'string', 'max:255'],
			'from_date' => ['nullable', 'date', 'date_format:Y-m-d'],
			'to_date' => ['nullable', 'date', 'date_format:Y-m-d'],
			'role_id' => ['nullable', 'integer'],
		];
	}
}
