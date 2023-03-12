<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Support\Facades\Gate;

trait ResourcePolicyTrait
{
    protected static $policy;

    public function __construct($resource)
    {
        parent::__construct($resource);

        if (!static::$policy && $resource && !is_array($resource) && ($policyClass = Gate::getPolicyFor(get_class($resource)))) {
            static::$policy = new $policyClass();
        }
    }
}
