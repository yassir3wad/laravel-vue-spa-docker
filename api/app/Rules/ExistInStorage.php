<?php

namespace App\Rules;

use App\Utils\Url;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;

class ExistInStorage implements ValidationRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (Url::isValidUrl($value) || Storage::has($value)) {
			return;
		}

		$fail(trans('validation.file', ['attribute' => trans("validation.attributes.$attribute")]));
	}
}
