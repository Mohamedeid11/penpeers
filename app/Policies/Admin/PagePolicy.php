<?php

namespace App\Policies\Admin;

class PagePolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'pages';
    protected $hasSystemProtection = TRUE;
    protected $systemProtectionActions = ['delete'];
}
