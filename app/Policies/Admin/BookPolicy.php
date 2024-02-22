<?php

namespace App\Policies\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy extends AdminResourceBasePolicy
{
    use HandlesAuthorization;

    protected $permissionCategory = 'books';

}
