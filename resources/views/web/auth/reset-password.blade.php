@extends('web.layouts.master')
@section('content')
    <!-- Start My Account Area -->
    <main class="pt-5 pb--55 bg-image bg-image--1 flex-fill border-90">
        <section class="container">
            <div class="row">
                <div class="col-lg-6 col-12 bg--white">
                    <form method="POST" action="{{route('password.update')}}">
                        {{@csrf_field()}}
                        <div class="account__form">
                            <h3 class="account__title mb-4">{{__('global.reset_password')}}</h3>
                            <input type="hidden" name="token" value="{{$token}}">
                            <div class="form-group mb-1">
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

                            <div class="form-group mb-1">
                                <label for="password">{{__('global.password')}}<span>*</span></label>
                                <div class="input-group">
                                    <input id="password" class="form-control @if($errors->has('password')) is-invalid @endif"
                                        name="password" type="password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text show-password"><i class="fa-solid fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('password') as $error)
                                            <p>{{$error}}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_confirmation">{{__('global.password_confirm')}}<span>*</span></label>
                                <div class="input-group">
                                    <input id="password_confirmation" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif"
                                        name="password_confirmation" type="password" required>
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
                            <div class="form__btn">
                                <button class="d-block mx-auto">{{__('global.reset')}}</button>
                            </div>
                            <div class="d-flex">
                                <a class="forget_pass d-flex ml-auto" href="{{route('web.get_login')}}">{{__('global.login_instead')}}</a>
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
