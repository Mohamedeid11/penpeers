@extends("web.layouts.master")
@section('heads')
<link rel="stylesheet" href="{{asset('css/web/contact.css')}}"/>
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--6', 'title' => __('global.contact_us'), 'sub_title' => __('global.contact_us')])
@section('content')
@include('web.partials.flashes')
@if ($errors->any())
<div class="container mt-3 mb-1">
    <div class="row">
        @foreach ($errors->all() as $error)
        <label class="alert alert-danger w-100 alert-dismissible fade show" role="alert">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </label>
        @endforeach
    </div>
</div>
@endif
<main class="wn_contact_area bg--white pt--80 pb--80">
    <div class="container">
        <div class="row">

            <!-- Contact form -->
            <section class="col-lg-8 col-12">
                <div class="contact-form-wrap">
                    <h2 class="contact__title">{{__('global.get_in_touch')}}</h2>
                    <p>{{ __('global.get_in_touch_subheading') }}</p>
                    <form action="{{route('web.contactUs')}}" method="post">
                        @csrf
                        <div class="single-contact-form space-between">
                            <input type="text" name="first_name" placeholder="{{__('global.first_name')}}*" value="{{old('first_name')}}" required>
                            <input type="text" name="last_name" placeholder="{{__('global.last_name')}}*" required value="{{old('last_name')}}">
                        </div>
                        <div class="single-contact-form space-between">
                            <input type="email" name="email" placeholder="{{__('global.email')}}*" required value="{{old('email')}}">
                        </div>
                        <div class="single-contact-form message">
                            <textarea name="message" placeholder="{{ __('global.type_message') }}"
                                required>{{ old('message') }}</textarea>
                        </div>
                        <div class="single_contact_form form-group">
                            {!! htmlFormSnippet() !!}
                        </div>

                        <div class="mt-2 single_contact_form form-group">
                            <span class="recaptcha-error d-none alert alert-danger">Recaptcha is required</span>
                        </div>

                        @if($errors->has('g-recaptcha-response'))
                            @foreach($errors->get('g-recaptcha-response') as $error)
                                <div class="col-lg-12">
                                    <div class="single_contact_form form-group">
                                        <span id="error-captcha" class="alert alert-danger">
                                            {{($error)}}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="contact-btn">
                            <button type="submit">{{ __('global.send_email') }}</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Office info -->
            <div class="col-lg-4 col-12 md-mt-40 sm-mt-40">
                <div class="wn__address">
                    <h2 class="contact__title">{{ __('global.office_info') }}</h2>
                    <p>{{__('global.penpeers_service')}}</p>
                    <div class="wn__addres__wreapper">
                        <div class="single__address">
                            <i class="fa-solid fa-location-dot"></i>
                            <dl class="content">
                                <dt>{{__('global.address')}}:</dt>
                                <dd>{{$contact_info[2]['value']}}</dd>
                            </dl>
                        </div>

                        <div class="single__address">
                            <i class="fa-solid fa-phone"></i>
                            <dl class="content">
                                <dt>{{ __('global.phone_number') }}:</dt>
                                <dd>{{str_replace(',', ' , ', $contact_info[1]['value'])}}</dd>
                            </dl>
                        </div>

                        <div class="single__address">
                            <i class="fa-solid fa-envelope"></i>
                            <dl class="content">
                                <dt>{{__('global.email_address')}}:</dr>
                                <dd>{{str_replace(',', ' , ', $contact_info[0]['value'])}}</p>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
