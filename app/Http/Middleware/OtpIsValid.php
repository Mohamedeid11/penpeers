<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

class OtpIsValid
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
        $user =   auth()->guard('admin')->user();
        $user_google_2fa_secret = $user->google2fa_secret;

        if (Session::get('admin_otp_valid') && $user_google_2fa_secret ==  decrypt(Session::get('admin_otp_valid'))) {
            return $next($request);
        }

        return redirect(route('admin.2fa.twoFactorAuthOTP'));
    }
}
