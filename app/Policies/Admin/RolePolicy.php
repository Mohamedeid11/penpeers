<?php

namespace App\Policies\Admin;

class RolePolicy extends AdminResourceBasePolicy
{
    protected $systemAdminRequired = true;
    protected $hasSystemProtection = true;
    protected $hasSystemProtectionErrorMessage = 'admin.sysroles_cannot_edited';
}
