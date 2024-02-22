<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscriptions\CreateSubscription;
use App\Http\Requests\Admin\Subscriptions\DeleteSubscription;
use App\Http\Requests\Admin\Subscriptions\EditSubscription;
use App\Models\Subscription;
use App\Services\Admin\SubscriptionsCrudService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class SubscriptionsController extends BaseAdminController
{
    private $subscriptionsCrudService;

    /**
     * SubscriptionsController constructor.
     * Authorize requests using App\Policies\SubscriptionPolicy.
     * @param SubscriptionsCrudService $subscriptionsCrudService
     */
    public function __construct(SubscriptionsCrudService $subscriptionsCrudService)
    {
        parent::__construct(Subscription::class);
        $this->authorizeResource(Subscription::class);
        $this->subscriptionsCrudService = $subscriptionsCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $subscriptions = $this->subscriptionsCrudService->getAllSubscriptions();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $subscription = new Subscription;
        return view('admin.subscriptions.create-edit', compact('subscription'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSubscription $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateSubscription $request)
    {
        $this->subscriptionsCrudService->CreateSubscription($request->all());
        return redirect(route('admin.subscriptions.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Subscription $Subscription
     * @return Application|Factory|View|Response
     */
    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.create-edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditSubscription $request
     * @param Subscription $Subscription
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditSubscription $request, Subscription $subscription)
    {
        $this->subscriptionsCrudService->updateSubscription($subscription, $request->all());
        return redirect(route('admin.subscriptions.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteSubscription $request
     * @param Subscription $Subscription
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function destroy(DeleteSubscription $request, Subscription $subscription)
    {
        $this->subscriptionsCrudService->DeleteSubscription($subscription);
        return redirect(route('admin.subscriptions.index'));
    }

    /**
     * @param DeleteSubscription $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteSubscription $request){
        $this->subscriptionsCrudService->batchDeleteSubscriptions($request->all());
        return redirect(route('admin.subscriptions.index'));
    }

}
