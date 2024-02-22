<?php

namespace App\Policies\Admin;

use App\Models\Admin;

class NewsletterEmailPolicy extends AdminResourceBasePolicy
{
    protected $permissionCategory = 'newsletter_emails';

    public function create(Admin $admin){
        return parent::doAny($admin, 'send');
    }
}
