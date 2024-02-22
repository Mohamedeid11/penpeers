@extends('web.layouts.master')
@include('web.partials.page-title', ['background' => 'bg-image--6', 'title' => __('global.specialist_servieces_page'), 'sub_title' => __('global.consult')])
@section('content')
    @include('web.partials.flashes')
    <main class="pb--55 bg--white flex-fill container">
        <!-- Image -->
        <img src="{{asset('images/web/specialbanner.png')}}" class="w-100 px-3">

        <!-- Consult form -->
        <section class="row">
            <div class="col-lg-6 col-12 mx-auto">
                <form method="POST" action="{{route('web.consult_form')}}">
                    @csrf
                    <div class="account__form">
                        <div class="input__box">
                            <label for="user_name"> {{__('global.name')}} <span>*</span></label>
                            <input type="text" class="form-control" id="user_name" placeholder="{{__('global.enter_name')}}" name="name" required>
                        </div>
                        <div class="input__box">
                            <label for="user_email">{{__('global.email_address')}}<span>*</span></label>
                            <input type="email" class="form-control" name="email" id="user_email" placeholder="{{__('global.enter_email')}}" required>
                        </div>
                        <div class="input__box">
                            <label for="user_phone">{{__('global.phone')}}<span>*</span></label>
                            <input type="phone" class="form-control" name="phone" id="user_phone" placeholder="{{__('global.enter_phone')}}" required>
                        </div>
                        <div class="input__box">
                            <label for="user_req">{{__('global.request_for')}}<span>*</span></label>
                            <select id="user_req" class="form-control" name="occupation_id" required>
                                <option value="" disabled selected>{{ __('global.help_me_find') }}
                                </option>
                                @foreach ($occupations as $occupation)
                                    <option value="{{$occupation->id}}">{{$occupation->name}}</option>
                                @endforeach
                            </select>
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
                        
                        <div class="form__btn mt-5 mx-auto w-max-content">
                            <button type="submit">{{ __('global.send_request') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
