<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    protected $fillable = ['name', 'guard_name', 'group'];

    /**
     * @return mixed
     */
    public static function scopeWhereGroup($q, array|string $group)
    {
        return $q->whereIn('group', (array) $group);
    }
}
