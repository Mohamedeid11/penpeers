<?php

namespace App\Policies\Admin;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminResourceBasePolicy
{
    use HandlesAuthorization;
    protected $permissionCategory = null;
    protected $systemAdminRequired = False;
    protected $hasSystemProtection = False;
    protected $hasSystemProtectionErrorMessage = null;
    protected $systemProtectionActions = ['edit', 'delete'];

    protected function doAny(Admin $admin, $action, $model=null){
        if (! $this->checkPermission($admin, $action)){
            return $this->deny(__("admin.not_enough_perms"));
        }
        if (! $this->checkSystemAdminRequirement($admin)){
            return $this->deny(__("admin.not_enough_perms"));
        }
        if (! $this->checkSystemProtection($model, $action)){
            return $this->deny($this->hasSystemProtectionErrorMessage?__($this->hasSystemProtectionErrorMessage):'');
        }
        return $this->allow();
    }
    public function viewAny(Admin $admin){
        return $this->doAny($admin, 'view');
    }
    public function view(Admin $admin, $model){
        return $this->doAny($admin, 'view', $model);
    }
    public function create(Admin $admin){
        return $this->doAny($admin, 'add');
    }
    public function update(Admin $admin, $model){
        return $this->doAny($admin, 'edit', $model);
    }
    public function translate(Admin $admin, $model){
        return $this->doAny($admin, 'translate', $model);

    }
    public function delete(Admin $admin, $model=null){
        return $this->doAny($admin, 'delete', $model);

    }
    public function restore(Admin $admin, $model){
        return $this->doAny($admin, 'edit', $model);
    }
    public function forceDelete(Admin $admin, $model){
        return $this->doAny($admin, 'delete');

    }

    private function checkPermission($admin, $action) : bool
    {
        if ($this->permissionCategory){
            $permissionCategory = $this->permissionCategory;
            return (bool) $admin->role()->whereHas('permissions', function ($query) use ($action, $permissionCategory){
                $query->where(['name'=>"{$action}_{$permissionCategory}"]);
            })->first();
        }
        return true;
    }

    private function checkSystemAdminRequirement($admin): bool
    {
        if($this->systemAdminRequired){
            return (bool) $admin->role->is_system;
        }
        return true;
    }

    private function checkSystemProtection($model, $action): bool
    {
        if($this->hasSystemProtection && in_array($action, $this->systemProtectionActions)){
            return $model?!$model->is_system:true;
        }
        return true;
    }
}
