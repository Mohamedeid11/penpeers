<?php

namespace App\Policies\Admin;

use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class AdminPolicy extends AdminResourceBasePolicy
{
    protected $systemAdminRequired = true;
    protected $hasSystemProtection = true;
    protected $hasSystemProtectionErrorMessage = 'admin.sysadmins_cannot_edited';


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $admin
     * @return mixed
     */
    public function update(Admin $user, $admin)
    {
        $user_role = $user->role()->where(['name'=>'admin'])->where(['is_system'=>true])->first();
        if (!$user_role){
            return Response::deny(__("admin.not_enough_perms"));
        }
        if($user->id == $admin->id){
            return Response::deny(__('admin.cannot_edit_yourself'));
        }
        return $admin->is_system ? Response::deny(__('admin.sysadmins_cannot_edited')) : Response::allow();

    }
    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $admin
     * @return mixed
     */
    public function delete(Admin $user, $admin=null)
    {
        $user_role = $user->role()->where(['name'=>'admin'])->where(['is_system'=>true])->first();
        if (!$user_role){
            return Response::deny(__("admin.not_enough_perms"));
        }
        if (!$admin) {
            return Response::allow();
        }
        if($user->id == $admin->id){
            return Response::deny(__('admin.cannot_delete_yourself'));
        }
        return $admin->is_system ? Response::deny(__('admin.sysadmins_cannot_deleted')) : Response::allow();

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $admin
     * @return mixed
     */
    public function restore(Admin $user, $admin)
    {
        return $this->update($user, $admin);

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $admin
     * @return mixed
     */
    public function forceDelete(Admin $user, $admin)
    {
        return $this->delete($user, $admin);

    }
}
