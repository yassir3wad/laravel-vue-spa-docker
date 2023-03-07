<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
	/**
	 * Validate and update the given user's profile information.
	 *
	 * @param array<string, string> $input
	 */
	public function update(User $user, array $input): void
	{
		if (isset($input['type']) && $input['type'] == 'information') {
			$this->updateInformation($user, $input);
			return;
		}

		$validatedData = Validator::make($input, [
			'name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'alpha_num', 'max:255', Rule::unique('users')->ignore($user->id)],
			'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
			'image' => ['nullable', 'file', 'image', 'max:2048'],
		])->validateWithBag('updateProfileInformation');

		if ($input['email'] !== $user->email &&
			$user instanceof MustVerifyEmail) {
			$this->updateVerifiedUser($user, $input);
		} else {
			if (isset($validatedData['image']) && $validatedData['image'] instanceof UploadedFile) {
				$validatedData['image'] = $validatedData['image']->store('users');
			}

			$user->forceFill(Arr::only($validatedData, ['name', 'username', 'email', 'image']))->save();
		}
	}

	private function updateInformation(User $user, array $input)
	{
		$validatedData = Validator::make($input, [
			'dob' => ['nullable', 'date', 'date_format:Y-m-d', 'before:today'],
			'bio' => ['nullable', 'string'],
			'mobile' => ['nullable', 'phone:INTERNATIONAL'],
		])->validateWithBag('updateProfileInformation');

		$user->forceFill(Arr::only($validatedData, ['dob', 'bio', 'mobile']))->save();
	}

	/**
	 * Update the given verified user's profile information.
	 *
	 * @param array<string, string> $input
	 */
	protected function updateVerifiedUser(User $user, array $input): void
	{
		$user->forceFill([
			'name' => $input['name'],
			'email' => $input['email'],
			'email_verified_at' => null,
		])->save();

		$user->sendEmailVerificationNotification();
	}
}
