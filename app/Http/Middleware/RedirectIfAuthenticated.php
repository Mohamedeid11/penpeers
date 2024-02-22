<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()&& ! Route::is('admin.*')) {
                return redirect(RouteServiceProvider::HOME);
            }
        }
        if (Auth::guard('admin')->check() && Route::is('admin.*')) {
            return redirect(route('admin.get_dashboard'));
        }
        else if (Auth::guard('web')->check() && ! Route::is('admin.*')) {
            return redirect(route('web.get_landing'));
        }

        return $next($request);
    }
}
