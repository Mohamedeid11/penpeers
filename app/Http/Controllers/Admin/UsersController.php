<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Families\DeleteFamily;
use App\Http\Requests\Admin\Users\CreateUser;
use App\Http\Requests\Admin\Users\DeleteUser;
use App\Http\Requests\Admin\Users\EditUser;
use App\Http\Requests\Admin\Users\FakePaymentRequest;
use App\Models\LoginAttempt;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     * Authorize requests using App\Policies\Admin\UserPolicy.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        return view('admin.users.create-edit', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUser $request)
    {
        $plan = Plan::first();

        $data = $request->only([
            'role_id', 'account_type' ,'country_id','plan_id', 'nationality_id', 'username', 'email', 'name',
            'phone', 'date_of_birth', 'date_of_death', 'occupation', 'nickname', 'club_name',
            'gender', 'original_family', 'interests', 'bio', 'facebook', 'twitter', 'instagram', 'snapchat'
        ]);
        $data['language'] = LaravelLocalization::getCurrentLocale();
        $data['active'] = ($request->filled('active')) ? true : false;
        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('uploads/users', ['disk' => 'public']);
            $data['profile_pic'] = $path;
        }
        if ($request->hasFile('club_logo')) {
            $path = $request->file('club_logo')->store('uploads/users', ['disk' => 'public']);
            $data['club_logo'] = $path;
        }
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user = User::create(array_merge($data , ['plan_id' => $plan->id]));
        if ($request->get('translations')){

            $user->add_translations($request->get('translations'));
        }
        $request->session()->flash('success', __('admin.success_add', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $records = [];
        $payment = new Payment();
        $hits = [];

        $hits = json_encode($hits);
        return view('admin.users.show', compact('records', 'user', 'payment', 'hits'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.create-edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditUser $request
     * @param User $user
     * @return void
     */
    public function update(EditUser $request, User $user)
    {
        $data = $request->only([
            'role_id', 'account_type' ,'country_id','plan_id', 'nationality_id', 'username', 'email', 'name',
            'phone', 'date_of_birth', 'date_of_death', 'occupation', 'nickname', 'club_name',
            'gender', 'original_family', 'interests', 'bio', 'facebook', 'twitter', 'instagram', 'snapchat'
        ]);
        $data['active'] = $request->filled('active') ? true : false;
        if ($request->hasFile('profile_pic')) {
            Storage::disk('public')->delete($user->profile_pic);
            $path = $request->file('profile_pic')->store('uploads/users', ['disk' => 'public']);
            $data['profile_pic'] = $path;
        }
        if ($request->hasFile('club_logo')) {
            Storage::disk('public')->delete($user->club_logo);
            $path = $request->file('club_logo')->store('uploads/users', ['disk' => 'public']);
            $data['club_logo'] = $path;
        }
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        if ($request->get('translations')){

            $user->add_translations($request->get('translations'));
        }        $request->session()->flash('success', __('admin.success_edit', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteUser $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(DeleteUser $request, User $user)
    {
        Storage::disk('public')->delete($user->profile_pic);
        Storage::disk('public')->delete($user->club_logo);
        $user->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }
    /**
     * Batch remove specified resources from storage
     *
     * @param DeleteFamily $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function batchDestroy(DeleteUSer $request)
    {
        $ids = json_decode($request->input('bulk_delete'), true);
        $target_users = User::whereIn('id', $ids);
        if (isset($target_users->club_logo)){
            Storage::disk('public')->delete($target_users->pluck('club_logo'));
        }
        Storage::disk('public')->delete($target_users->pluck('profile_pic'));
//        DeleteFamily::whereHas('creator', function (Builder $query) use ($ids) {
//            $query->whereIn('id', $ids);
//        })->delete();
        $target_users->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }

    /**
     *
     * @param $user_id
     * @return false|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggle_active($user_id)
    {
        $this->authorize('viewAny', User::class);
        $user = User::findOrFail($user_id);
        $user->active = !$user->active;
        $user->save();
        return json_encode([
            'status' => 0,
            'message' => __("admin.status_success")
        ]);
    }

    public function fakePayment(FakePaymentRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->only(['plan_id', 'payment_type', 'value']);
        $plan = Plan::find($data['plan_id']);
        $data['confirmed'] = $request->filled('confirmed');
        $data['transaction_id'] = "FAKE" . date('Y-m-d hh:ii:ss');
        $data['start_date'] = Carbon::now();
        $data['end_date'] = Carbon::now()->addYears($plan->years);
        $payment = Payment::create([
            'user_id' => $user->id, 'transaction_id' => $data['transaction_id'], 'payment_type' => $data['payment_type'],
            'confirmed' => $data['confirmed'], 'value' => $data['value']
        ]);
        $user->user_plans()->create(['plan_id' => $data['plan_id'], 'payment_id' => $payment->id, 'start_date' => $data['start_date'], 'end_date' => $data['end_date']]);
        session()->flash('success',  __('admin.success_add', ['thing' => __('global.payment')]));
        return redirect(route('admin.users.show', ['user' => $user->id]));
    }

    public function loginAttempts()
    {
        $loginAttempts = LoginAttempt::orderBy('created_at', 'desc')->paginate(100);
        return view('admin.login_attempts.index', compact('loginAttempts'));
    }

    public function getUser($id){
        $user = User::with('interests', 'country')->find($id, ['id', 'name', 'email', 'profile_pic', 'mobile_number', 'country_id', 'bio', 'gender']);
        return response()->json($user);
    }

    public function impersonate($user_id)
    {
        $user = User::findOrFail($user_id);
        $this->authorize('impersonate', $user);
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        Auth::guard('web')->login($user, true);
        session()->regenerate();
        Session::put('web_auth_token', Auth::guard('web')->user()->createToken('token')->plainTextToken);
        return redirect()->intended(route('web.get_landing'));
    }
}
