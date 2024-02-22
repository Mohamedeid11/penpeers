<?php

namespace App\Policies\Admin;

class ContactMessagePolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'contact_messages';
}
