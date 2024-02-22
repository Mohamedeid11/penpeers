@extends('web.layouts.master')
@section('content')
<!-- Start My Account Area -->
<main class="pt-5 pb--55 bg-image bg-image--1 flex-fill border-90">
    <div class="container">
        @include('web.partials.flashes')

        <div class="row">
            <div class="col-lg-6 col-12 bg--white">
                <form method="POST" action="{{route('web.send_book_request',['slug'=> $slug] )}}">
                    {{@csrf_field()}}
                    <div class="account__form border-0">
                        @if(!Auth::user())

                            <h3 class="account__title">{{ __('global.book_request') }}</h3>
                            <p class="mb-4">{{__('global.have_account')}} <a href="{{route('web.get_login')}}" style="color: var(--secondary-color); text-decoration: underline;">{{__('global.login')}}</a></p>
                            <div class="form-group mb-1">
                                <label for="name">{{__('global.name')}}<span>*</span></label>
                                <input id="name" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{old('name')}}" name="name" type="name" required placeholder="{{__('global.enter_name')}}">
                                @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('name') as $error)
                                    <p>{{$error}}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="form-group mb-1">
                                <label for="email">{{__('global.email')}}<span>*</span></label>
                                <input id="email" class="form-control @if($errors->has('email')) is-invalid @endif" type="email" name="email" required autocomplete value="{{old('email')}}" required placeholder="{{__('global.enter_email')}}">
                                @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('email') as $error)
                                    <p>{{$error}}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>


                            <div class="form-group single-contact-form message">
                                <label for="bio">{{__('global.bio')}}<span>*</span></label>
                                <textarea class="form-control @if($errors->has('bio')) is-invalid @endif" placeholder="{{__('global.write_bio')}}" name="bio" id="bio" required></textarea>
                                @if($errors->has('bio'))
                                <div class="invalid-feedback">
                                    @foreach($errors->get('bio') as $error)
                                    <p>{{$error}}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        @else
                            <h3 class="account__title mb-4">{{ __('global.book_request') }}</h3>
                        @endif

                        <div class="form-group mb-1">
                            <label for="message">{{__('global.message')}}<span>*</span></label>
                            <textarea class="form-control @if($errors->has('message')) is-invalid @endif" name="message" id="message" placeholder="{{__('global.enter_message')}}"></textarea>
                            @if($errors->has('message'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('message') as $error)
                                <p>{{$error}}</p>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <br>

                        {{--                            This for Recaptcha--}}
                        <div class="newsletter__box">
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
                                                    This for Recaptcha
                        <br>

                        <div class="form-group mb-1">
                            <div class="custom-control custom-checkbox ">
                                <input id="terms" class="custom-control-input @if($errors->has('terms')) is-invalid @endif" name="terms" value="accept" type="checkbox" required>
                                <label for="terms" class="custom-control-label">
                                    <span>{{ __('global.book_request_msg') }} </span>
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
                        <div class="form-group col-12 text-center">
                            <button type="submit" class="btn btn-primary mt-4"> {{__('global.send')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<!-- End My Account Area -->


@endsection
