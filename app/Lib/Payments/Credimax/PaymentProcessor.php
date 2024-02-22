<?php

namespace App\Lib\Payments\Credimax;

use App\Models\Plan;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PaymentProcessor extends PaymentConfig
{
    private $headers;
    private $data;
    private $userRepository;
    public function __construct(SettingRepositoryInterface $settingRepository, UserRepositoryInterface $userRepository, $username, $headers, $data)
    {
        parent::__construct($settingRepository);
        $this->userRepository = $userRepository;
        $this->headers = $headers;
        $this->data = $data;
        $this->user = $this->userRepository->getBy('username', $username);
    }

    // public function validity(): bool
    // {
    //     Log::info($this->data);
    //     Log::info($this->headers);
    //     $plan = Plan::where(['id' => $this->data['metadata']['plan']])->first();
    //     $upgrade = $this->data['metadata']['upgrade'];
    //     $this->setUpPlan($plan, $upgrade);
    //     $this->subscriptionPrice = $data['amount'] ?? $this->userSubscriptionPrice;
    //     $subscriptionPrice = $this->userSubscriptionPrice;
    //     $privateKey = $this->sk;
    //     $postedHash = $this->headers->get('Hashstring', '');
    //     $id = $this->data['id'] ?? '';
    //     $currency = $this->data['currency'] ?? $this->currency;
    //     $gateway_reference = $this->data['reference']['gateway'] ?? '';
    //     $payment_reference = $this->data['reference']['payment'] ?? '';
    //     $status = $this->data['status'] ?? '';
    //     $created = $this->data['transaction']['created'] ?? '';
    //     $toBeHashedString = 'x_id' . $id . 'x_amount' . $subscriptionPrice . 'x_currency' . $currency . 'x_gateway_reference' . $gateway_reference . 'x_payment_reference' . $payment_reference . 'x_status' . $status . 'x_created' . $created . '';
    //     $myHashString = hash_hmac('sha256', $toBeHashedString, $privateKey);
    //     if ($myHashString == $postedHash && Arr::has($this->data, 'status') && $this->data['status'] == 'CAPTURED') {
    //         return true;
    //     }
    //     return false;
    // }

    public function validity(): bool
    {
        $plan = Plan::where(['id'=>$this->data['plan']])->first();
        $upgrade = $this->data['upgrade'];
        $this->setUpPlan($plan, $upgrade);

        if(!isset($this->data['resultIndicator']) || empty($this->data['resultIndicator'])) {
            //Payment Failed
           return false;
        }

        if($this->data['successIndicator'] != $this->data['resultIndicator']){
            //Payment Failed
            return false;
        }

        return true;
    }
    public function getTransactionId(): string
    {
        // return $this->data['id'] ?? '';
        return $this->data['resultIndicator'] ?? '';
    }
}
