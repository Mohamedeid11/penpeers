<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginRequest;
use App\Http\Requests\Web\Auth\RegisterRequest;
use App\Models\Book;
use App\Models\BookParticipant;
use App\Models\BookParticipationRequest;
use App\Models\Country;
use App\Models\EmailInvitation;
use App\Models\Plan;
use App\Services\AuthService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class UserAuthController extends BaseWebController
{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }
    public function showRegisterForm(Request $request, $username = null)
    {
        if ($username) {
            $username = str::remove('@', $username);
            $user = $this->authService->getUser($username);
        } else {
            $user = null;
        }

        $company = request() ? request()->input('company') : null;
        $hash = request() ? request()->input('hash') : null;
        $email = request() ? request()->input('email') : null;
        if ($company && $hash && $email) {

            if (User::where('email', $email)->count() > 0) {
                session()->flash('success', __('global.registered_email') . " , " . __('global.please_login'));
                return redirect(route('web.get_login'))->withInput();
            }

            $company = User::where(['id' => $company])->first();
            if ($company) {
                $hash = hash_equals($hash, sha1($company->email)) ? $hash : null;
                if (!$hash) {
                    session()->flash('error', __('global.link_expired') );
                    return redirect(route('web.get_register'))->withInput();
                }
            }
        }
        $trialPeriod = Setting::where(['name' => 'trial_days'])->first()->value;
        $countries = Country::where('active', 1)->pluck('name', 'id');
        // form opened from the linke sent to email
        $email_decoded = 0;
        $book_participation_request_id = 0;
        if ($request->invitaion && $request->hashing) {
            $email = base64_decode($request->hashing);
            $record = BookParticipationRequest::where('email', $email)->first();
            $book_participation_request_id = $record ?  $record->id : 0;
        }

        $plans = Plan::where('account_type', 'personal')->get();

        return view('web.auth.register', compact('company', 'hash', 'trialPeriod', 'user', 'email', 'countries', 'book_participation_request_id', 'plans'));
    }
    public function showLoginForm()
    {
        return view('web.auth.login');
    }
    public function showResetRequestForm()
    {
        return view('web.auth.forgot-password');
    }
    public function submitResetRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status),'success' => __('global.email_sent_checkInbox')])
            : back()->withErrors(['email' => __($status)]);
    }
    public function showResetForm($token)
    {
        return view('web.auth.reset-password', ['token' => $token]);
    }
    public function submitReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('web.get_login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    public function verify()
    {
        return view('web.auth.verify-email');
    }
    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }
    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return redirect(route('web.get_landing'));
    }
    public function register(RegisterRequest $request)
    {
        $plan = Plan::first();
        $reg = $this->authService->register($request->only(['name', 'email', 'password', 'username', 'role', 'account_type', 'country_id', 'book_participation_request_id']), $plan->id);

        if ($reg) {
            return redirect(route('web.get_login'));
        }
        
        return back()->withInput();
    }
    public function sign(EmailVerificationRequest $request)
    {
        $request->fulfill();
        session()->flash('success', __('global.confirmed_email') );
        return redirect(route('web.get_login'));
    }
    public function sign_not_logged_in($id, string $hash)
    {
        $user = User::find($id);
        $user_id = $user->id;
        if (!hash_equals(
            (string) $user_id,
            (string) $user->getKey()
        )) {
            abort(403);
        }

        if (!hash_equals(
            $hash,
            sha1($user->getEmailForVerification())
        )) {
            abort(403);
        }
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        session()->flash('success', __('global.verified_email'));
        return redirect(route('web.get_login'));
    }
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', __('global.verification_sent'));
    }
    /**
     * @return RedirectResponse
     */
    public function assureLogin(): RedirectResponse
    {
        Session::put('web_auth_token', Auth::guard('web')->user()->createToken('token')->plainTextToken);
        return redirect()->back();
    }

    public function getPlansByAccountType($account_type)
    {
        return json_encode(Plan::where('account_type', $account_type)->get());
    }
}
