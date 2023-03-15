<?php

namespace App\Http\Resources\Dashboard\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Lang;

class PermissionResource extends JsonResource
{
	static $useTranslations = false;

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'group' => self::$useTranslations && Lang::has("permissions.{$this->group}") ? trans("permissions.{$this->group}") : $this->group,
			'name' => self::$useTranslations && Lang::has("permissions.{$this->name}") ? trans("permissions.{$this->name}") : $this->name
		];
	}
}
