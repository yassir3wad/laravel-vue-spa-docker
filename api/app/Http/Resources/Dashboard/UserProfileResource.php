<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Dashboard\Role\PermissionResource;
use App\Utils\Url;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserProfileResource extends JsonResource
{
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
			'permissions' => $this->getPermissionsViaRoles()->pluck('name')->toArray(),
			'created_at' => $this->created_at->format('Y-m-d h:i a'),
			'updated_at' => $this->updated_at->format('Y-m-d h:i a'),
		];
	}
}
