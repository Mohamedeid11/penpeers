@extends('web.layouts.dashboard')
@section('heads')
<link href="https://goSellJSLib.b-cdn.net/v1.6.2/css/gosell.css" rel="stylesheet" />
<script type="text/javascript" src="https://goSellJSLib.b-cdn.net/v1.6.2/js/gosell.js" id="go-sell-script"></script>
<script type="text/javascript" src="https://credimax.gateway.mastercard.com/checkout/version/57/checkout.js" id="credi-max-script"></script>
@endsection
@section('content')
<main class="main-page">
    @include('web.partials.dashboard-header', ['title' => __('global.account_status'), 'sub_title' => __('global.your_account_status_page'), 'current' => '<li class="active">'.__('global.account_status').'</li>'])
    @include('web.partials.flashes')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <!-- Subsription status -->
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h3 class="h5">{{ __('global.subscription_status') }}</h3>
                            </div>
                        </div>

                        <div class="panel-body d-flex flex-column">
                            <div class="card-body">
                                @if ($trial !== false)
                                    <p class="card-title text-danger h5">
                                        <i class="fa-solid fa-xmark"></i>
                                        {{__("global.trial_period")}}
                                        <strong>({{$trial}} {{__("global.days_remaining")}})</strong>
                                    </p>
                                    <button data-toggle="modal" data-target="#payment-modal" class="btn btn-success ml-auto mr-3 d-block" id="pay-btn"><i class="fa-solid fa-check"></i> {{__("global.subscribe_now")}}</button>
                                @else
                                    @if($user->validity)
                                        @if($user->plan->remaining < 30)
                                            <p class="card-title text-secondary h5"><i
                                                    class="fa fa-exclamation-triangle"></i>
                                                {{ __('global.plan_about_to_expire') }}</p>
                                        @else
                                            <p class="card-title text-primary h5"><i class="fa-solid fa-check"></i> {{__("global.valid_plan")}}</p>
                                        @endif
                                        <table class="table table-bordered text-heavy">
                                            <tbody>
                                                <tr>
                                                    <th>{{__("global.subscribed")}} </th>
                                                    {{-- <td>{{$user->plan->payment->created_at}}</td> --}}
                                                    <td>{{$user->plan->start_date}}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__("global.valid_until")}}</th>
                                                    <td>{{$user->plan->end_date}} ({{ $user->plan->remaining }} {{__('global.days_remaining')}})</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__("global.invoice")}}</th>
                                                    <td>{{$user->plan->payment->transaction_id}}</td>
                                                </tr>
                                                @if($user->account_type === 'corporate' && $user->items > 0)
                                                <tr>
                                                    <th>{{__("global.employees")}}</th>
                                                    <td>{{$user->employees()->count()}}/{{$user->items}}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        @if($user->can_renew)
                                        <button data-toggle="modal" data-target="#payment-modal" class="btn btn-success ml-auto mr-3 d-block"><i class="fa-solid fa-check"></i> {{__("global.renew")}}</button>
                                        @endif
                                        @if($user->can_upgrade)
                                        <button data-toggle="modal" data-target="#upgrade-payment-modal" class="btn btn-success ml-auto mr-3 d-block"><i class="fa-solid fa-check"></i> {{__("global.upgrade")}}</button>
                                        @endif
                                    @else
                                        @if (count($user->user_plans) == 0)
                                            <p class="card-title text-danger h5"><i class="fa-solid fa-xmark"></i>
                                                {{ __('global.plan_notvalid') }}</p>
                                            <p class="card-text text-light">
                                                {{__("global.complete_sbscription")}}
                                            </p>
                                            <button data-toggle="modal" data-target="#payment-modal" class="btn btn-success ml-auto mr-3 d-block"><i class="fa-solid fa-check"></i> {{__('global.subscribe_now')}}</button>
                                        @else
                                            <p class="card-title text-danger h5"<i class="fa-solid fa-xmark"></i> {{__('global.plan_notvalid')}}</p>
                                            <button data-toggle="modal" data-target="#payment-modal"
                                                class="btn btn-success ml-auto mr-3 d-block"><i class="fa-solid fa-check"></i>
                                                {{__('global.subscribe_now')}}</button>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="col-md-12 mb-5" id="delete-section">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h3 class="h5">{{__('global.delete_my_account')}}</h3>
                            </div>
                        </div>
                        <div class="panel-body d-flex flex-column">
                            <p>{{__('global.click_delete_account')}}</p>
                            <form method="get" action="{{route('web.dashboard.account.MailToDeleteAccount')}}" id="danger-form">
                                @csrf
                                <button type="submit" class="btn btn-danger ml-auto d-block"><i class="fa-solid fa-trash-can"></i> {{__('global.delete_my_account')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal Section -->
    <div id="root"></div>
    <div class="modal fade" tabindex="-1" id="payment-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-bottom">
                <div class="modal-header">
                    <h2 class="modal-title h5">{{__('global.choose_plan')}}</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-5 bg--gray">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            @foreach($plans as $plan)
                                @if($plan->priceForUser($user) > 0)
                                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                        @if((! $user->plan && $plan->id == $user->plan_id) || ($user->plan && $plan->id == $user->plan->plan_id))
                                            <div class="card w-100 active h-100 border-0 bg-white" onclick="activePlan(this)">
                                                <div class="card-body text-center border-top d-flex flex-column">
                                                    @if($user->plan)
                                                        <span class="d-block text-primary h8 mb-3 m-auto text-decoration">{{__('global.current')}}</span>
                                                    @endif
                                                    <h6 class="card-title text-primary">{{$plan->price}} {{__('global.USD')}}</h6>
                                                    <h6 class="card-subtitle mb-2 text-secondary mb-3">{{__('global.'.$plan->period)}}</h6>
                                                    <button onclick="choosePlanCredi(this, '{{$plan->id}}')" class="btn btn-primary align-self-center mt-auto">{{__('global.choose')}}</button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="card w-100 h-100 border-0 bg-white" onclick="activePlan(this)">
                                                <div class="card-body text-center border-top d-flex flex-column">
                                                    <h5 class="card-title text-primary">{{$plan->price}} {{__('global.USD')}}</h5>
                                                    <h6 class="card-subtitle mb-2 text-secondary mb-3">{{__('global.'.$plan->period)}}</h6>
                                                    <button onclick="choosePlanCredi(this, '{{$plan->id}}')" class="btn btn-primary align-self-center mt-auto">{{__('global.choose')}}</button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="upgrade-payment-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-bottom">
                <div class="modal-header">
                    <h2 class="modal-title h5">{{__('global.choose_plan')}}</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-5 bg--gray">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            @if(count($upgradePlans) > 0)
                                @foreach($upgradePlans as $plan)
                                    @if($plan->priceForUser($user) > 0)
                                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                            @if($user->plan && $plan->id == $user->plan->plan_id)
                                                <div class="card w-100 active h-100 border-0 bg-white" onclick="activePlan(this)">
                                                    <div class="card-body text-center border-top d-flex flex-column">
                                                        @if(count($upgradePlans) == 1)
                                                            <span class="d-block text-primary h8 mb-3 m-auto text-decoration">{{__('global.current')}}</span>
                                                            <h6 class="card-title text-primary]">{{$plan->price}} {{__('global.USD')}}</h6>
                                                            <h6 class="card-subtitle mb-2 text-secondary mb-3">{{__('global.'.$plan->period)}}</h6>
                                                            <h6 class="text-primary border-bottom">{{__('global.upgrade')}}: {{$plan->priceForUser($user)}} {{__('global.USD')}}</h6>
                                                            <button onclick="choosePlanCredi(this, '{{$plan->id}}', true)" class="btn btn-primary align-self-center mt-auto">{{__('global.choose')}}</button>
                                                        @else
                                                            <span class="d-block text-primary h8 mb-3 m-auto text-decoration">{{__('global.current')}}</span>
                                                            <h6 class="card-title text-primary">{{$plan->price}} {{__('global.USD')}}</h6>
                                                            <h6 class="card-subtitle mb-2 text-secondary mb-3">
                                                                {{ __('global.'.$plan->period) }}
                                                            </h6>
                                                            <button class="btn primary-btn align-self-center mt-auto" disabled>{{__('global.choose')}}</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card w-100 h-100 border-0 bg-white" onclick="activePlan(this)">
                                                    <div class="card-body text-center border-top d-flex flex-column">
                                                        <h6 class="card-title text-primary">{{$plan->price}} {{__('global.USD')}}</h6>
                                                        <h6 class="card-subtitle mb-2 text-secondary mb-3">{{__('global.'.$plan->period)}}</h6>
                                                        <h6 class="text-primary">{{__('global.upgrade')}}: {{$plan->priceForUser($user)}} {{__('global.USD')}}</h6>
                                                        <button onclick="choosePlanCredi(this, '{{$plan->id}}', true)" class="btn btn-primary align-self-center mt-auto">{{__('global.choose')}}</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @else
                            <h3 class="text-white">{{__('global.no_upgrade')}}</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Modal Section -->

</main>

@endsection
@section('scripts')
@include('web.partials.axiosinit')
<script>
    @if($trial !== false || $user->can_renew || $user->can_upgrade || !$user->plan)

    function pay() {
        goSell.openLightBox();
    }

    function activePlan(e) {
        document.querySelectorAll(".card").forEach(elm => elm.classList.remove('active'))
        e.classList.add("active");
    }

    function choosePlanCredi(e, plan, upgrade = false) {
        const data = {
            'plan': plan
        };
        data['upgrade'] = upgrade;
        if (upgrade) {
            data['upgrade'] = true
        }
        axios.post('{{route('web.dashboard.account.preparePayment.crediMax')}}', data, {withCredentials: true})
            .then(function(response) {

                if (response.data.status == 1) {

                    const script = load_js_credi(response.data.preparer.postUrl);
                    script.addEventListener('load', function() {

                        configCrediMax(response.data.preparer);

                    });
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    }

    function load_js_credi(postUrl) {

        document.getElementById("credi-max-script")?.remove();
        document.getElementById("root").innerHTML = "";
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.id = "credi-max-script";
        script.type = 'text/javascript';
        script.src = "https://credimax.gateway.mastercard.com/checkout/version/57/checkout.js";
        script.dataset.err = "errorCallback";
        script.dataset.cancel = "{{route('web.dashboard.account.status')}}"
        script.dataset.complete = postUrl;
        head.appendChild(script);
        return script;

    }

    function configCrediMax(payment_preparer) {

        Checkout.configure({
            merchant: payment_preparer.gateway_merchant_id,
            order: {
                amount: payment_preparer.userSubscriptionPrice,
                currency: payment_preparer.currency,
                description: payment_preparer.labels['item_desc'],
                id: payment_preparer.orderId
            },
            session: {
                id: payment_preparer.sessionId
            },
            interaction: {
                merchant: {
                    name: payment_preparer.labels['merchant_name'],
                    address: {
                        line1: payment_preparer.labels['address']
                    },
                    email: payment_preparer.labels['support_email'],
                    phone: payment_preparer.labels['suuport_phone'],
                    logo: payment_preparer.labels['logo']
                },
                operation: 'PURCHASE',
                locale: payment_preparer.language, //ar_SAen_US
                theme: 'default',
                displayControl: {
                    billingAddress: 'HIDE', //OPTIONAL  READ_ONLY  MANDATORY
                    customerEmail: 'HIDE',
                    orderSummary: 'HIDE',
                    shipping: 'HIDE'
                }
            }
        });
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                Checkout.showLightbox();
            });
        });

    }

    @endif

    goSell.showResult({
        callback: response => {
            console.log("callback", response);
        }
    });

    form = document.getElementById("danger-form")
    form.onsubmit = function() {
        event.preventDefault();
        swal({
            title: "{{ __('global.want_delete_account') }}",
            type: "error",
            showCancelButton: !0,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "{{__('admin.do_it')}}",
            cancelButtonText: "{{__('admin.cancel')}}",
            closeOnConfirm: false
        }, function(confirm) {
            if (confirm) {
                form.submit();
            }
        });
    }
</script>

@endsection
