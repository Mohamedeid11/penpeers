<?php

namespace App\Policies\Admin;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PublishRequestPolicy extends AdminResourceBasePolicy
{
    use HandlesAuthorization;

    protected $permissionCategory = 'publish_requests';

    public function approve(Admin $admin){
        return parent::doAny($admin, 'approve');
    }
}
