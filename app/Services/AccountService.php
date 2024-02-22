<?php

namespace App\Services;

use App\Events\TracingEmail;
use App\Lib\Payments\Credimax\PaymentPreparer;
use App\Lib\Payments\Credimax\PaymentProcessor;
use App\Models\Payment;
use App\Models\RecordType;
use App\Models\User;
use App\Models\UserInterest;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\InterestRepositoryInterface;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\UserValidityTrait;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;


class AccountService
{
    use UserValidityTrait;
    private $countryRepository;
    private $userRepository;
    private $settingRepository;
    private $planRepository;
    private $interestRepository;

    public function __construct(
        CountryRepositoryInterface $countryRepository,
        UserRepositoryInterface $userRepository,
        SettingRepositoryInterface $settingRepository,
        PlanRepositoryInterface $planRepository,
        InterestRepositoryInterface $interestRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->userRepository = $userRepository;
        $this->settingRepository = $settingRepository;
        $this->planRepository = $planRepository;
        $this->interestRepository = $interestRepository;
    }
    public function getCountries()
    {
        return $this->countryRepository->all();
    }

    public function getInterests(){
        return $this->interestRepository->all();
    }

    public function editDetails(array $data, array $files)
    {
//         dd($data['interests']);
        $user = User::find(auth()->guard('web')->id());
        $data = Arr::only($data, ['name','country_id', 'username', 'image_cropping', 'revertImage', 'bio','social_links', 'mobile_number', 'gender', 'interests']);
        if (Arr::has($data, 'revertImage')) {
            $default = $user->account_type === 'personal' ? 'user.png' : 'corporate.png';
            $image = "uploads/profile/" . time() . ".png";
            Storage::disk('public')->copy("defaults/" . $default, $image);
            $data['profile_pic'] = $image;
            Storage::disk('public')->delete($user->image);
        } else {

            if (isset($files['image'])) {
                Storage::disk('public')->delete($user->image);
                $image = $data['image_cropping'];  // your base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = Carbon::now()->timestamp . $user->username . '.' . 'png';
                $data['profile_pic'] = "uploads/profile/" . $imageName;
                Storage::disk('public')->put("uploads/profile/" . $imageName, base64_decode($image));
            } else {
                $data['profile_pic'] = $user->profile_pic;
            }
        }

        unset($data['revertImage']);

        unset($data['image_cropping']);
        if(isset($data['interests'])){
            UserInterest::where('user_id', $user->id)->delete();
            foreach($data['interests'] as $interest){
                UserInterest::create(['user_id' => $user->id, 'interest_id' => $interest]);
            }
        }else{
            UserInterest::where('user_id', $user->id)->delete();
        }

        $user->social_links = $data['social_links'];

        $user->update($data);
        session()->flash('success', __('global.account.success_update') );
    }

    public function changePassword(array $data): array
    {
        $user = auth()->guard('web')->user();
        if (!Hash::check($data['current_password'], $user->password)) {
            return ['current_password' =>  "Wrong Password"];
        }
        $user->update(['password' => Hash::make($data['password'])]);
        session()->flash('password_changed', __('global.account.changing_password'));
        return [];
    }

    public function toggleStatus()
    {
        $user = auth()->guard('web')->user();
        $user->update(['hidden' => !$user->hidden]);
        if ($user->hidden) {
            session()->flash('success', __('admin.success_hidden', ['thing' => __('global.user')]));
        } else {
            session()->flash('success', __('admin.success_published', ['thing' => __('global.user')]));
        }
    }

    public function fakePayment()
    {
        $user = auth()->guard('web')->user();
        Payment::factory(['user_id' => $user->id, 'confirmed' => true])->create();
        session()->flash('success',  __('admin.success_add', ['thing' => __('global.user')]));
    }
    public function getSubscriptionPrice()
    {
        return $this->settingRepository->getBy('name', 'subscription_price')->value;
    }

    public function getPaymentPreparer()
    {
        return App::make(PaymentPreparer::class);
    }

    public function processPayment($username, array $data, $headers)
    {

        $data = array_merge($data,request()->session()->get('data'));
        $paymentProcessor = App::make(PaymentProcessor::class, ['username' => $username, 'data' => $data, 'headers' => $headers]);

        if ($paymentProcessor->validity()) {

            $user = $this->userRepository->getBy('username', $username);
            if ($user->user_plan && !$paymentProcessor->isUpgrade()) {

                $start_date = (new Carbon($user->user_plan->end_date))->addDay();
                $end_date = (new Carbon($user->user_plan->end_date))->addDay()->addYear();

            } else {

                $start_date = Carbon::now();
                $end_date = Carbon::now()->addYears($paymentProcessor->getPlan()->years);

            }

            $payment = Payment::create([

                'user_id' => $user->id,
                'transaction_id' => $paymentProcessor->getTransactionId(),
                'payment_type' => 'credit',
                'confirmed' => true,
                'value' => $paymentProcessor->userSubscriptionPrice

            ]);

            $user->user_plans()->create(['plan_id' => $paymentProcessor->getPlan()->id, 'payment_id' => $payment->id, 'start_date' => $start_date, 'end_date' => $end_date]);

            $email = $user->email;
            event(new TracingEmail(null , $user->id , null ,"Success Payment"));

            Mail::send('mail.successful_payment', ['name' => $user->name], function ($message) use ($email) {
                $message->to($email)
                    ->subject("Payment Successful");
            });

        }

    }

    public function deleteAccount()
    {
        $user = auth()->guard('web')->user()->id;
        User::find($user)->delete();
    }

    public function getPlans()
    {
        return auth()->guard('web')->user()->getAvailablePlans();
    }
    public function getUpgradePlans()
    {
        return auth()->guard('web')->user()->getAvailablePlans(true);
    }
    public function getPlan(array $data)
    {
        return $this->planRepository->get($data['plan']);
    }
}
