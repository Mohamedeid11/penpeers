<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Web\BaseWebController;
use App\Http\Requests\Web\Account\EditDetailsRequest;
use App\Http\Requests\Web\Account\PaymentRequest;
use App\Http\Requests\Web\Account\PreparePaymentRequest;
use App\Http\Requests\Web\Profile\ChangePasswordRequest;
use App\Lib\Payments\Credimax\PaymentPreparer as CredimaxPaymentPreparer;
use App\Mail\DeleteMyAccountApprove;
use App\Models\User;

use App\Traits\UserValidityTrait;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;
use PDF;

class AccountController extends BaseWebController
{
    use UserValidityTrait;
    private $accountService;

    /**
     * AccountController constructor.
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        parent::__construct();
        $this->middleware('check_plan_validity', ['only' => ['editDetails', 'changePassword', 'toggleStatus']]);
        $this->accountService = $accountService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = auth()->guard('web')->user();
        $countries = $this->accountService->getCountries();
        $interests = $this->accountService->getInterests();
        $userInterests = $user->interests()->pluck('interest_id')->toArray();
        return view('web.dashboard.account.edit', compact('user',  'countries', 'interests', 'userInterests'));
    }

    /**
     * @param EditDetailsRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function editDetails(EditDetailsRequest $request)
    {
        $this->accountService->editDetails($request->all(), $request->allFiles());
        return redirect(route('web.dashboard.account.edit.show'));
    }

    /**
     * @param ChangePasswordRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $errors = $this->accountService->changePassword($request->all());
        if (!empty($errors)) {
            return redirect(route('web.dashboard.account.edit.show') . "#password-change")->withErrors($errors)->withInput();
        }
        return redirect(route('web.dashboard.account.edit.show') . "#password-change");
    }
    public function accountStatus()
    {
        // dd('ahmed');

        $user = auth()->guard('web')->user();
        $trial = $user->isInTrial(True);
        $plans = $this->accountService->getPlans();
        $upgradePlans = $this->accountService->getUpgradePlans();
        return view('web.dashboard.account.status', compact('user', 'trial', 'plans', 'upgradePlans'));
    }
    public function toggleStatus()
    {
        $this->accountService->toggleStatus();
        return back();
    }
    public function getPaymentData($username, PaymentRequest $request)
    {
        $this->accountService->processPayment($username, $request->all(), $request->headers);
        session()->flash('success', __('global.payment.payment_completed_successfully'));
        return back();
    }
    public function accountDangerZone()
    {
        $user = auth()->guard('web')->user();
        return view('web.account.danger', compact('user'));
    }
    public function checkAccountValidity()
    {
        $id = auth()->guard('web')->id();
        $user = User::find($id);
        return $user->validity ? 1 : 0;
    }


    public function MailToDeleteAccount()
    {

        $user = auth()->guard('web')->user();

        $email = $user ? $user->email : "";
        Mail::to($email)->send(new DeleteMyAccountApprove($user));
        return back()->with('success',  __('global.email_sent_successfully') .' , '. __('global.use_email_to_delete_your_account'));

    }

    public function deleteAccount(Request $request,User $user)
    {
        $this->accountService->deleteAccount();
        auth()->guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('web.get_landing'));
    }
    public function preparePayment(PreparePaymentRequest $request)
    {
        $plan = $this->accountService->getPlan($request->all());
        $availablePlans = auth()->user()->getAvailablePlans($request->upgrade)->pluck('id')->toArray();
        if (in_array($plan->id, $availablePlans)) {

            $payment_preparer = app()->make(PaymentPreparer::class, ['plan' => $plan, 'upgrade' => $request->upgrade]);

            // $user = auth()->user();
            // $email = $user->email;
            // Mail::send('mail.successful_payment', ['name' => $user->name], function ($message) use ($email) {
            //     $message->to($email)
            //         ->subject("Payment Successful");
            // });

            return ['status' => 1, 'preparer' => $payment_preparer];
        }
        return ['status' => 0];
    }

    public function preparePaymentCredi(PreparePaymentRequest $request)
    {
        $plan = $this->accountService->getPlan($request->all());
        $availablePlans = auth()->user()->getAvailablePlans($request->upgrade)->pluck('id')->toArray();

        if (in_array($plan->id, $availablePlans)) {

            $payment_preparer = app()->make(CredimaxPaymentPreparer::class, ['plan' => $plan, 'upgrade' => $request->upgrade]);

            return ['status' => 1, 'preparer' => $payment_preparer];
        }
        return ['status' => 0];
    }

    public function downloadInvoice()
    {
        $user = auth()->user();
        $pdf = PDF::loadView('web.account.invoice-template', compact('user'));
        // return $pdf->download('invoice.pdf');
        return $pdf->stream('invoice.pdf');
    }
}
