@extends('web.layouts.master')
@section('content')
<!-- Start My Account Area -->
<main class="pt-5 pb--55 bg-image bg-image--1 flex-fill border-90">
    <section class="container">
        <div class="row">
            @include('web.partials.flashes')

            <div class="col-lg-6 col-12 bg--white">
                <form method="POST">
                    {{@csrf_field()}}
                    <input type="hidden" name="next" value="{{request()->input('next')}}">
                    <div class="account__form">
                        <h3 class="account__title mb-4">{{ __('global.welcome_back') }}</h3>
                        <div class="form-group">
                            <label for="username">{{ __('global.user_mail') }} <span>*</span></label>
                            <input id="username" class="form-control @if($errors->has('username')) is-invalid @endif" type="text" name="username" autofocus required value="{{old('username')}}">
                            @if($errors->has('username'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('username') as $error)
                                <p>{{$error}}</p>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('global.password') }}<span>*</span></label>
                            <div class="input-group">
                                <input id="password" class="form-control @if($errors->has('password')) is-invalid @endif" name="password" type="password" required>
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text show-password"><i class="fa-solid fa-eye-slash" aria-hidden="true"></i></button>
                                </div>
                                @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('password') as $error)
                                    <p>{{$error}}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                        @if(count($errors) > 0)
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
                        @endif

                        <div class="form__btn d-flex flex-column">
                            <label class="label-for-checkbox mb-4" for="remember">
                                <input id="remember" class="input-checkbox" name="remember" value="forever" type="checkbox">
                                <span>{{ __('global.remember_me') }}</span>
                            </label>
                            <button class="align-self-center">{{__('global.login')}}</button>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a class="forget_pass"
                                href="{{ route('password.request') }}">{{ __('global.lost_password') }}</a>
                            <a class="forget_pass"
                                href="{{ route('web.get_register') }}">{{ __('global.dont_have_account') }}</a>
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
    const togglePasswordBtn = document.querySelector(".show-password");
    const inputPassword = togglePasswordBtn.parentElement.previousElementSibling;
    togglePasswordBtn.addEventListener("click", () => {
        if(inputPassword.type === "password") inputPassword.type = "text";
        else inputPassword.type = "password"
    })
</script>
@endsection
