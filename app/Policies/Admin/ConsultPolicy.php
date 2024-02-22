<?php

namespace App\Policies\Admin;

use Illuminate\Auth\Access\HandlesAuthorization;

class ConsultPolicy extends AdminResourceBasePolicy
{
    use HandlesAuthorization;

    protected $permissionCategory = 'consults';

}
