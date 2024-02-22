@extends('web.layouts.master')
@section('content')
<!-- Start My Account Area -->
<main class="pt-5 pb--55 bg-image bg-image--1 flex-fill border-90" id="vue-app">
    <section class="container">
        <div class="row">
            @include('web.partials.flashes')

            <div class="col-lg-6 col-12 bg--white">
                <form method="POST" action="{{route('web.post_register')}}" enctype='multipart/form-data'>
                    @csrf
                    <div class="account__form">
                        <h3 class="account__title">{{__('global.register_for_free')}}</h3>

                        <div class="form-group mb-1 row p-3">
                            @if($email)
                            <input type="hidden" name="email" value="{{$email}}">
                            <input type="hidden" name="book_participation_request_id" value="{{$book_participation_request_id}}">
                            @endif
                        </div>
{{--                        <div class="form-group mb-1">--}}
{{--                            <label for="plan-input">{{__('global.plan')}}<sup class="required">*</sup></label>--}}
{{--                            <select name="plan" id="plan-input" class="form-control cu_input @if($errors->has('plan')) is-invalid @endif" required v-model="selected_plan" value="{{inp_value(null, 'plan')}}">--}}
{{--                                <option value="" disabled selected>{{__('global.choose_plan')}}</option>--}}
{{--                                @foreach($plans as $plan)--}}
{{--                                    <option value="{{$plan->id}}">{{$plan->period}} - {{$plan->price}} {{__('global.USD')}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            @if($errors->has('plan'))--}}
{{--                            <div class="invalid-feedback">--}}
{{--                                @foreach($errors->get('plan') as $error)--}}
{{--                                <span>{{$error}}</span>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}

                        <div class="form-group mb-1">
                            <label for="name">{{__('global.name')}}<span>*</span></label>
                            <input id="name" class="form-control @if($errors->has('name')) is-invalid @endif" type="text" name="name" autofocus required value="{{old('name')}}">
                            @if($errors->has('name'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('name') as $error)
                                <p>{{$error}}</p>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="form-group mb-1">
                            <label for="country_id-input">{{__('global.country')}}<span>*</span></label>
                            <select name="country_id" id="country_id-input" class="form-control cu_input @if($errors->has('country_id')) is-invalid @endif" required>
                                <option value=""> Select Country </option>
                                @foreach ($countries as $country_id => $country_name)
                                    <option value="{{$country_id}}">{{ $country_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('country_id'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('country_id') as $error)
                                        <p>{{$error}}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(!$email)
                            <div class="form-group mb-1">
                                <label for="email">{{__('global.email')}}<span>*</span></label>
                                <input id="email" class="form-control @if($errors->has('email')) is-invalid @endif" type=" email" name="email" required autocomplete value="{{old('email')}}">
                                @if($errors->has('email'))
                                <div class=" invalid-feedback">
                                    @foreach($errors->get('email') as $error)
                                    <p>{{$error}}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        @endif

                        <div class="form-group mb-1">
                            <label for="username">{{__('global.username')}}<span>*</span></label>
                            <input id="username" class="form-control @if($errors->has('username')) is-invalid @endif" type="text" name="username" required value="{{old('username')}}">
                            @if($errors->has('username'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('username') as $error)
                                        <p>{{$error}}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>


                        <div class="form-group mb-1">
                            <label for="password">{{__('global.password')}}<span>*</span></label>
                            <div class="input-group">
                                <input id="password" class="form-control @if($errors->has('password')) is-invalid @endif" name="password" type="password" required>
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text show-password"><i class="fa-solid fa-eye-slash" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            @if($errors->has('password'))
                                <div class="invalid-feedback d-block">
                                    @foreach($errors->get('password') as $error)
                                    <p>{{$error}}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-1">
                            <label
                                for="password_confirmation">{{ __('global.password_confirm') }}<span>*</span></label>
                            <div class="input-group">
                                <input id="password_confirmation" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif" name="password_confirmation" type="password" required>
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text show-password"><i class="fa-solid fa-eye-slash" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            @if($errors->has('password_confirmation'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('password_confirmation') as $error)
                                <p>{{$error}}</p>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <br>

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
                            <br>
                        @endif

                        <div class="form-group">
                            <div class="custom-control custom-checkbox ">
                                <input id="terms" class="custom-control-input @if($errors->has('terms')) is-invalid @endif" name="terms" value="accept" type="checkbox" required>
                                <label for="terms" class="custom-control-label">
                                    <a href="#"
                                        target="_blank">{{ __('global.terms_conditions') }}</a>
                                </label>
                                @if($errors->has('terms'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('terms') as $error)
                                    <p>{{$error}}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form__btn">
                            <button type="submit" class="mx-auto d-block">{{__('global.register')}}</button>
                        </div>
                        <div class="d-flex">
                            <a class="forget_pass d-flex ml-auto"
                                href="{{ route('web.get_login') }}">{{ __('global.already_user') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<!-- End My Account Area -->
@endsection
@section('scripts')
<script>
    // Toggle password
    const togglePasswordBtns = document.querySelectorAll(".show-password");
    Array.from(togglePasswordBtns).forEach((btn, i) => {
        const inputPassword = btn.parentElement.previousElementSibling;
        btn.addEventListener("click", () => {
            if(inputPassword.type === "password") inputPassword.type = "text";
            else inputPassword.type = "password"
        })
    })
</script>
@endsection
