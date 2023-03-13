<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
	use HasFactory;

    public const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';

    protected $fillable = ['name', 'guard_name'];
}
