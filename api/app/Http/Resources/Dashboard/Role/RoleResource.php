<?php

namespace App\Http\Resources\Dashboard\Role;

use App\Http\Resources\Dashboard\ResourcePolicyTrait;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RoleResource extends JsonResource
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
			'permissions_count' => $this->permissions_count,
			'permissions' => $this->when($this->resource->relationLoaded('permissions'), fn() => PermissionResource::collection($this->permissions)),
			'created_at' => $this->created_at->format('M j (D) g:i a'),
			'actions' => [
				'can_view' => self::$policy->view(Auth::user(), $this->resource),
				'can_update' => self::$policy->update(Auth::user(), $this->resource),
				'can_delete' => self::$policy->delete(Auth::user(), $this->resource),
			]
		];
	}
}
