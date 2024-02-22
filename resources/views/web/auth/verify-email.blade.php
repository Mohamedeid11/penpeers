@extends('web.layouts.master')
@section('content')
<section class="pt-5 pb--55 bg-image bg-image--1 flex-fill border-90">
    @include('web.partials.flashes')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12 bg--white">
                <div class="account__form">
                    <h3 class="account__title border-bottom">{{ __('global.notice') }} </h3>
                    <div class="mt-2">
                        <p class="mb-4">{{ __('global.verify_email_message') }}</p>
                        <p>{{ __('global.verify_email_page.message-2') }}
                        <a href="mailto:{{ Auth::user()->email }}" style="color: #ce7852;">{{ Auth::user()->email }}</a>
                            {{ __('global.verify_email_page.message-3') }} </p>
                        <br>
                        <p>{{ __('global.verify_email_page.not_received_message') }} </p>
                        <form class="d-inline-block" action="{{ route('verification.send') }}"
                            method="POST">
                            {{ @csrf_field() }}
                            <button class="btn btn-primary m-2" type="submit">{{ __('global.verify_email_page.button') }}</button>
                        </form>
                        <small class="d-block">{{ __('global.verify_email_page.note_message') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
