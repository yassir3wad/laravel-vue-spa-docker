<?php

namespace App\Actions\Fortify;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Fortify;

class ProfileInformationUpdatedResponse implements \Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => trans('messages.successfully_updated')], 200)
            : back()->with('status', Fortify::PROFILE_INFORMATION_UPDATED);
    }
}
