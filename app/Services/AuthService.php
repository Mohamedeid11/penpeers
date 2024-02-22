<?php

namespace App\Services;

use App\Events\UserCreated;
use App\Mail\informLeadAuthorForAcceptParticipationRequest;
use App\Models\BookParticipationRequest;
use App\Models\EmailInvitation;
use App\Models\Role;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Repositories\Interfaces\BookParticipantRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PhpParser\Node\Expr\Cast\Object_;

class AuthService
{
    protected $userRepository;
    protected $bookParticipantRepository;

    public function __construct(UserRepositoryInterface $userRepository, BookParticipantRepositoryInterface $bookParticipantRepository)
    {
        $this->userRepository = $userRepository;
        $this->bookParticipantRepository = $bookParticipantRepository;
    }

    /**
     * @param $request
     * @return RedirectResponse
     */
    public function login($request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');
        $remember = $request->filled('remember');
        $user = User::where('username', $request->username)->first();

        if (Auth::attempt($credentials, $remember)) {

            // Auth::login($user, $request->get('remember'));

            $request->session()->regenerate();

            Session::put('web_auth_token', Auth::guard('web')->user()->createToken('token')->plainTextToken);

            if ($user->email_verified_at == null) {
                return redirect(route('verification.notice'));
            }

            return redirect()->route($user->can_renew || ! $user->validity ? 'web.dashboard.account.status' : 'web.dashboard.index');

        } else {

            $credentials['email']=$credentials['username'];
            unset($credentials['username']);

            if (Auth::attempt($credentials, $remember)) {

                // Auth::login($user, $request->get('remember'));

                $request->session()->regenerate();

                Session::put('web_auth_token', Auth::guard('web')->user()->createToken('token')->plainTextToken);

                if ($user->email_verified_at == null) {
                    return redirect(route('verification.notice'));
                }

                return redirect()->route($user->can_renew || ! $user->validity ? 'web.dashboard.account.status' : 'web.dashboard.index');

            }
        }

        return back()->withErrors([
            'username' => __('admin.login_error') ,
        ])->withInput();
    }

    public function register(array $data, $plan = NULL): bool
    {
            $company = request() ? request()->input('company') : null;
            $hash = request() ? request()->input('hash') : null;
            $email = request() ? request()->input('email') : null;

            if ($company && $hash && $email) {
                $company = $this->userRepository->all(true)->where(['id' => $company])->first();

                if ($company) {
                    $hash = hash_equals($hash, sha1($company->email)) ? $hash : null;
                    if (!$hash) {
                        session()->flash('error', __('global.link_expired'));
                        return false;
                    } else {
                        if (!in_array($data['email'], $company->employees->pluck('email')->toArray())) {
                            session()->flash('error', __('global.non_registered_penpeers'));
                            return false;
                        }

                        $data['company_id'] = $company->id;
                        $data['email'] = $email;
                        $data['email_verified_at'] = now();
                    }
                }
            }

        if ( $data['name'] != 'admin' && $data['name'] != 'penpeers'  ) {

            $role = 'author';
            $role = Role::where(['name' => $role])->first();
            $role = $role ?: Role::where(['name' => 'author'])->first();
            $data['account_type'] = "personal";
            $data['password'] = Hash::make($data['password']);
            $data['role_id'] = $role->id;
            $data['plan_id'] = $plan;
            $data['language'] = LaravelLocalization::getCurrentLocale();
            $user = $this->userRepository->create($data);
//            event(new UserCreated($user->email, $user->id));

//            create record for book participation
            if (isset($data['book_participation_request_id'])) {

                $user->update(['email_verified_at'=> now()]);
                $id = $data['book_participation_request_id'];
                $record = BookParticipationRequest::find($id);
                $email_invitation = EmailInvitation::where('email', $email)->get()->last();
                if (isset($email_invitation) && $email_invitation != null) {
                    $email_invitation->update(['status' => 1]);
                }
                $data_request = [
                    'book_id' => $record->book_id,
                    'user_id' => $user->id,
                    'status' => 1,
                    'book_role_id' => 2,
                    'answered_at' => now()
                ];
                $this->bookParticipantRepository->create($data_request);

                //Send Email inform lead author request accepted
                $lead_author = $record->book->lead_author;

                $url = route('web.dashboard.author_participants');
                $url_type =  'accepted_unregistered_author';

                $book_url = '<a class="text-underline text-secondary font-weight-bold"
                                    href="'. $url .'">'. $record->book->title .'</a>';

                $lead_author->notify(new RealTimeNotification(   '<strong style="color: #ce7852;">'. $record->name .'</strong>'. ' has accepted  participating in your book ' . $book_url ,$url , $url_type));
                Mail::to($lead_author->email)->send(new  informLeadAuthorForAcceptParticipationRequest($record));

                session()->flash('success', __('global.success_registered_email') );

            } else {

                $user->sendEmailVerificationNotification();

                session()->flash('success', __('global.success_to_verify_registered_email') );

            }

            return true;

        } else {

            session()->flash('error', __('global.register_name_failed'));

            return false;

        }
    }

    public function logout($request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    public function getUser($username)
    {
        return $this->userRepository->getBy('username', $username);
    }
}
