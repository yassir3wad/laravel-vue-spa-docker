<?php

namespace App\Http\Requests\Dashboard\User;

use App\Http\Requests\Dashboard\BaseDashboardRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends BaseDashboardRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', Password::min(6)->letters()->numbers(), 'confirmed'],
        ];
    }
}
