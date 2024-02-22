<?php

namespace App\Policies\Admin;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OccupationPolicy extends AdminResourceBasePolicy
{
    use HandlesAuthorization;

    protected $permissionCategory = 'occupations';

}
