<?php

namespace App\Lib\Payments\Credimax;

use App\Models\Plan;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use Illuminate\Support\Str;

class PaymentConfig
{

    protected $subscriptionPrice;
    protected $user;
    protected $plan;
    protected $upgrade = false;
    protected $ref;

    private $settingRepository;

    public $hashString = null;
    public $currency;
    public $language;
    public $gateway_api_password;
    public $gateway_merchant_id;
    public $userSubscriptionPrice;
    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;

        $this->gateway_api_password = config('app.gateway_api_password');
        $this->gateway_merchant_id = config('app.gateway_merchant_id');
        $this->currency = config('app.gateway_default_currency');
        $this->language = locale() == 'ar' ? 'ar' : 'en';
        $this->ref =  Str::random(20);
    }
    public function setSubscriptionPrice()
    {
        $subscriptionPriceToTwoPlaces = number_format($this->subscriptionPrice, 2, '.', '');
        $subscriptionPriceToThreePlaces = number_format($this->subscriptionPrice, 3, '.', '');
        $this->userSubscriptionPrice = $this->currency == 'KWD' ? $subscriptionPriceToThreePlaces : $subscriptionPriceToTwoPlaces;
    }

    public function setUpPlan(Plan $plan, $upgrade = false): void
    {
        $this->upgrade = $upgrade;
        $this->plan = $plan;
        $this->subscriptionPrice = $upgrade ?  $plan->priceForUser($this->user) : $plan->price;
        $this->setSubscriptionPrice();
    }
    public function getPlan()
    {
        return $this->plan;
    }
    public function isUpgrade()
    {
        return $this->upgrade;
    }
}
