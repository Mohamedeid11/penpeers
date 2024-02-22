<?php

namespace App\Traits;

use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait UserValidityTrait
{
    public function getGeneralValidity($user)
    {
        if (!$user->validity) {
            return false;
        }
        return true;
    }
    public function getValidityLackFirstReason($user)
    {
        if (!$user->validity && !Str::contains(request()->route()->getName(), 'web.dashboard.account.status')) {
            if (count($user->user_plans) > 0) {
                return collect([
                    'title' => "Renew Your Plan",
                    // 'text' => 'Your profile cannot go public until you subscribe/renew your plan',
                    'url_text' => "Go Ahead",
                    'url' => route('web.dashboard.account.status')
                ]);
            }
            return collect([
                'title' => "Please pay your subscription fee first",
                // 'text' => 'Your profile cannot go public until you subscribe/renew your plan',
                'url_text' => "Go Ahead",
                'url' => route('web.dashboard.account.status')
            ]);
        }

        return null;
    }
}
