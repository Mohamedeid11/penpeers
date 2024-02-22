<?php

namespace App\Lib\Payments\Credimax;

use App\Models\Plan;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use Illuminate\Support\Str;

class PaymentPreparer extends PaymentConfig
{
    public $labels;
    public $postUrl;
    public $redirectUrl;
    public $transaction;
    public $sessionId;
    public $orderId;

    public function __construct(SettingRepositoryInterface $settingRepository, Plan $plan, $upgrade = false)
    {
        parent::__construct($settingRepository);
        $this->setUpUser();
        $this->setUpPlan($plan, $upgrade);
        $this->setUpLabels();
        $this->setUpData();
        $this->setUpSessionId();
    }

    private function setUpLabels()
    {
        $this->labels = [
            'cardNumber' => __('global.cardNumber'),
            'expirationDate' => __('global.expirationDate'),
            'cvv' => __('global.cvv'),
            'cardHolder' => __('global.cardHolder'),
            'actionButton' => __('global.actionButton'),
            'merchant_name' => __('global.merchantName'),
            'first_name' => $this->user->name,
            'middle_name' => $this->user->name,
            'last_name' => $this->user->name,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'country_code' => $this->user->country ? $this->user->country->key : '20',
            'number' => $this->user->mobile_number,
            'mobile' => $this->user->mobile_number,
            'item_name' => __('global.payment_item_name'),
            'item_desc' => __('global.payment_item_desc'),
            'charge_desc' => __('global.payment_charge_desc'),
            'statement_descriptor' => __('global.payment_statement_descriptor'),
            'support_email' => 'support@penpeers.com' ,
            'support_phone' => '0097317300000',
            'logo' => 'https://localhost/images/web/logo/logo22.png',
            'metadata' => ['plan' => $this->plan->id, 'upgrade' => $this->upgrade]
        ];
    }

    private function setUpUser()
    {
        $this->user = auth()->guard('web')->user() ?: auth()->guard('user')->user();
    }

    private function setUpSessionId()
    {
            $rand="penpeers-".mt_rand(1000000,9999999999);
            $ch = curl_init();
            $parameters = "apiOperation=CREATE_CHECKOUT_SESSION&interaction.operation=PURCHASE&apiPassword=$this->gateway_api_password&interaction.returnUrl=".$this->postUrl."&apiUsername=merchant.$this->gateway_merchant_id&merchant=$this->gateway_merchant_id&order.id=$rand&order.currency=$this->currency&order.amount=$this->userSubscriptionPrice";
			curl_setopt($ch, CURLOPT_URL,"https://credimax.gateway.mastercard.com/api/nvp/version/57");
			curl_setopt($ch, CURLOPT_POST, 8);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($ch);
			curl_close ($ch);
			parse_str($server_output, $output);
            $this->orderId = $rand;
			if($output['result']=="SUCCESS"){
                $this->sessionId =$output['session_id'];
                $data = [];
                $data['ref']=$rand;
				$data['successIndicator']=$output['successIndicator'];
				$data['session_ver']=$output['session_version'];
				$data['session_id']=$output['session_id'];
				// $data['amount']=$output['tot'];
				$data['trackid']="track-".rand(10000000,99999999999);
                $data['plan'] = $this->plan->id;
                $data['upgrade'] = $this->upgrade;

                //set data to session
                request()->session()->put('data', $data);
                request()->session()->put('successIndicator', $output['successIndicator']);
            }


    }

    private function setUpData()
    {
        $this->postUrl = route('account.pay', ['username' => $this->user->username]);
        $this->redirectUrl = route('web.dashboard.account.status');
        $subscriptionPrice = $this->userSubscriptionPrice;
        $gateway_merchant_id = $this->gateway_merchant_id;
        $gateway_api_password = $this->gateway_api_password;
        $post = $this->postUrl;
        $currency = $this->currency;
        $ref = $this->ref;
        $hash_string = "x_publickey${gateway_merchant_id}x_amount${subscriptionPrice}x_currency${currency}x_transaction${ref}x_post${post}";
        $trial = $this->user->isInTrial(True);
        $plan_true = $this->user->plan ? $this->user->plan->remaining >= 30 : true;
        $this->hashString = !$trial && $this->user->validity &&  $plan_true ? "" : hash_hmac('sha256', $hash_string, $gateway_api_password);
    }
}
