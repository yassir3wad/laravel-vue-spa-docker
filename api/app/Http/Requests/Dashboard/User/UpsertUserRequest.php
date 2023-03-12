<?php

namespace App\Http\Requests\Dashboard\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UpsertUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_num', 'max:255', Rule::unique(User::class)->ignore($this->user?->getKey())],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user?->getKey())],
            'mobile' => ['required', 'phone:INTERNATIONAL'],
            'password' => ['nullable', Rule::when(! $this->user, ['required']), 'string', Password::min(6)->letters()->numbers(), 'confirmed'],
            'image' => ['nullable', Rule::when(! $this->user, ['required']), 'file', File::types(config('upload.types.users.mimetypes'))->max(config('upload.types.users.max_size'))],
        ];
    }
}
