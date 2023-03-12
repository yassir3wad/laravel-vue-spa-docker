<?php

namespace App\Actions\Fortify;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Fortify;

class PasswordUpdateResponse implements \Laravel\Fortify\Contracts\PasswordUpdateResponse
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => trans('messages.successfully_updated')], 200)
            : back()->with('status', Fortify::PASSWORD_UPDATED);
    }
}
