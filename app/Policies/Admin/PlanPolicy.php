<?php

namespace App\Policies\Admin;

use App\Models\Admin;
use App\Policies\Admin\AdminResourceBasePolicy;

class PlanPolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'plans';
    protected $hasSystemProtection = true;
    public function update(Admin $admin, $model=null){
        $this->hasSystemProtection = false;
        return parent::update($admin, $model);
    }
}
