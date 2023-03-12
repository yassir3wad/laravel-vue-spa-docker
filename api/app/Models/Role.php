<?php

namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    public const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';
    protected $fillable = ['name', 'guard_name'];
}
