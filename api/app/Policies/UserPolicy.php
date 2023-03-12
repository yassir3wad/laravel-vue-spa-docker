<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Contracts\Auth\Access\Authorizable;

class UserPolicy
{
    public function viewAny(Authorizable $user)
    {
        return $user->can('viewAny user');
    }

    public function view(Authorizable $user, User $model)
    {
        return $user->can('view user');
    }

    public function create(Authorizable $user)
    {
        return $user->can('create user');
    }

    public function update(Authorizable $user, User $model)
    {
        return $user->can('update user') && $model->getKey() !== 1 && $model->isNot($user);
    }

    public function delete(Authorizable $user, User $model)
    {
        return $user->can('delete user') && $model->getKey() !== 1 && $model->isNot($user);
    }

    public function changeStatus(Authorizable $user, User $model)
    {
        return $user->can('changeStatus user') && $model->getKey() !== 1 && $model->isNot($user);
    }

    public function resetPassword(Authorizable $user, User $model)
    {
        return $user->can('resetPassword user') && $model->getKey() !== 1 && $model->isNot($user);
    }
}
