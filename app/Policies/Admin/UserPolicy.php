<?php

namespace App\Policies\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

class UserPolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'users';
    public function impersonate(Admin $user, User $user_instance){
        return  $user->is_system ? Response::allow() : Response::deny(__("admin.not_enough_perms"));
    }
}
