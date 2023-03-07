<?php

namespace App\Actions\Fortify;

class ProfileInformationUpdatedResponse implements \Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse
{
	public function toResponse($request)
	{
		return [
			'message' => trans('messages.successfully_updated')
		];
	}
}