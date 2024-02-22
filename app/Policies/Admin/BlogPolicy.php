<?php

namespace App\Policies\Admin;

use App\Models\Admin;

class BlogPolicy extends AdminResourceBasePolicy
{ 
    protected $permissionCategory = 'blogs';   

    public function approve(Admin $admin){
        return parent::doAny($admin, 'approve');
    }
}
