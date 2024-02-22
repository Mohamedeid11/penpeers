@extends('web.layouts.master')
@section('content')
    <!-- Start My Account Area -->
    <main class="pt-5 pb--55 bg-image bg-image--1 flex-fill border-90">
        <div class="container">
            <div class="row">
                @include('web.partials.flashes')

                <div class="col-lg-6 col-12 bg--white">
                    <form method="POST">
                        {{@csrf_field()}}
                        <div class="account__form">
                            <h3 class="account__title mb-4">{{ __('global.reset_password') }}</h3>

                            <div class="form-group mb-3">
                                <label for="email">{{__('global.email')}}<span>*</span></label>
                                <input id="email" class="form-control @if($errors->has('email')) is-invalid @endif"
                                        type="email" name="email" required autocomplete value="{{old('email')}}">
                                @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('email') as $error)
                                            <p>{{$error}}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="form__btn">
                                <button class="mx-auto d-block">{{ __('global.request') }}</button>
                            </div>
                            <div class="d-flex">
                                <a class="forget_pass d-flex ml-auto"
                                    href="{{ route('web.get_login') }}">{{ __('global.login_instead') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- End My Account Area -->
@endsection
