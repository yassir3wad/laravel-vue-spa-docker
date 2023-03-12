<?php

namespace App\Http\Resources\Dashboard;

use App\Policies\UserPolicy;
use App\Utils\Url;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
	use ResourcePolicyTrait;

	/**
	 * @var UserPolicy
	 */
	protected static $policy;

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'username' => $this->username,
			'image' => Url::isValidUrl($this->image) ? $this->image : Storage::url($this->image),
			'mobile' => $this->mobile,
			'bio' => $this->bio,
			'dob' => $this->dob,
			'status' => $this->status,
			'status_value' => trans('constants.' . $this->status->value),
			'created_at' => $this->created_at->format('M j (D) g:i a'),
			'actions' => [
				'can_view' => self::$policy->view(Auth::user(), $this->resource),
				'can_update' => self::$policy->update(Auth::user(), $this->resource),
				'can_delete' => self::$policy->delete(Auth::user(), $this->resource),
				'can_reset_password' => self::$policy->resetPassword(Auth::user(), $this->resource),
				'can_change_status' => self::$policy->changeStatus(Auth::user(), $this->resource),
			]
		];
	}
}
