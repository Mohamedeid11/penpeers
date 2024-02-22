<?php
namespace App\Services\Admin;

use App\Models\Subscription;
use App\Repositories\Eloquent\SubscriptionRepository;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class SubscriptionsCrudService {
    private $subscriptionRepository;
    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository){
        $this->subscriptionRepository = $subscriptionRepository;
    }
    public function getAllSubscriptions()
    {
       return $this->subscriptionRepository->paginate(100);
    }

    public function createSubscription(array $data)
    {
        $data =  Arr::only($data, ['name','email']);
        $this->subscriptionRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.subscription')]) );
    }

    public function updateSubscription(Subscription $subscription, array $data)
    {
        $data =  Arr::only($data, ['name','email']);
        $subscription->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.subscription')])  );
    }

    public function deleteSubscription(Subscription $subscription)
    {
        $subscription->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.subscription')]) );
    }

    public function batchDeleteSubscriptions(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_Subscriptions = $this->subscriptionRepository->getMany($ids);
        $this->subscriptionRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.subscription')]) );
    }
}
