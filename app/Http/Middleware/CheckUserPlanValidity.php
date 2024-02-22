<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class CheckUserPlanValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $user_plans = $user->user_plans;

        if($user->validity || (Route::currentRouteName() != 'web.dashboard.books.create' && $user->free_period_after_expiry))
        {
            return $next($request);

        } else {

            if(count($user_plans) > 0)
            {
                return redirect()->back()->with('error', __('middleware.user_plan.plan_validity.renew_error') );

            } else {

                return redirect()->back()->with('error', __('middleware.user_plan.plan_validity.renew_error') );
            }

        }

    }
}
