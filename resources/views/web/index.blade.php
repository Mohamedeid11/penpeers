@extends('web.layouts.master')
@section('heads')
<link href="{{ asset('css/web/home.css') }}" rel="stylesheet" />
<link href="{{ asset('css/web/books.css') }}" rel="stylesheet" />
<link href="{{ asset('css/web/authors.css') }}" rel="stylesheet" />
<link href="{{ asset('css/web/blogs.css') }}" rel="stylesheet" />
@endsection
@section('content')
<div class="slider-area brown__nav slider--15 slide__activation slide__arrow01 owl-carousel owl-theme">
    <!-- Start Single Slide -->
    <div class="slide animation__style10 bg-image bg-image--4 fullscreen align__center--left">
        <div class="container">
            @include('web.partials.flashes')
            <div class="row">
                <div class="col-lg-12">
                    <div class="slider__content">
                        <div class="contentbox">
                            <h2 class="text-white">PenPeers</h2>
                            <h2 class="text-white">{{__('global.platform')}}</h2>
                            <p class="text-white" style="font-size:18px;">{{__('global.first_platform')}}</p>
                            <a class="shopbtn" href="{{auth()->check()? route('web.dashboard.index'): route('web.get_register')}}">{{auth()->check() ? __('global.start_writing') : __('global.register_for_free')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Single Slide -->
    <!-- Start Single Slide -->
    <div class="slide animation__style10 bg-image bg-image--8 fullscreen align__center--left">
        <div class="container">
            @include('web.partials.flashes')
            <div class="row">
                <div class="col-lg-12">
                    <div class="slider__content">
                        <div class="contentbox">
                            <h2 class="text-white">{{__('global.write')}}</h2>
                            <h2 class="text-white">{{__('global.collaborate')}}</h2>
                            <h2 class="text-white">{{__('global.publish')}}</h2>
                            <p class="text-white" style="font-size:18px;">{{__('global.dont_write_alone')}}</p>
                            <a class="shopbtn" href="{{auth()->check()? route('web.dashboard.index'): route('web.get_register')}}">{{auth()->check()? __('global.start_writing') : __('global.register_for_free')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Single Slide -->
</div>

@if(! auth()->check() || (auth()->check() && ! auth()->user()->validity))
<!-- Pricing -->
<section class="team_member_part py-5 ft_font bg--gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section__title mb-4">
                    <h2 class="text-center">{!!__('global.subscription_plans')!!}</h2>
                    @if(! auth()->check())
                        <p class="text-center w-75 mx-auto">{!!__('global.pricing_desc')!!}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row m-0 w-100 justify-content-center pricing">
            @foreach($personalPlans as $plan)
            <div class="col-lg-3 col-6">
                @include('web.partials.plan-card')
            </div>
            @endforeach
        </div>
        @if(auth()->check())
        <div class="banner_btn d-flex justify-content-center mt-3">
            <a href="{{route('web.dashboard.account.status')}}" class="btn btn-danger rounded text-white">{{__('global.account_status')}}</a>
        </div>
        @endif
    </div>
</section>
@endif

<!-- Our books -->
@if(count($sample_books))
<section class="wn__bestseller__area bg--white ptb--80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section__title text-center">
                    <h2>{!! __('global.our_latest_books') !!}</h2>
                    <p>{{__('global.latest_books_heading')}}</p>
                </div>
            </div>
        </div>
        <ul class="nav nav-pills mt-4 justify-content-center">
            <li class="nav-item mx-2">
                <button class="btn btn-outline-primary active" id="home-tab" data-toggle="tab" data-target="#home"
                    type="button"
                    role="tab" aria-controls="home" aria-selected="true">{{__('global.sample_books')}}</button>
            </li>
            <li class="nav-item mx-2">
                <button class="btn btn-outline-primary" id="profile-tab" data-toggle="tab" data-target="#profile"
                    type="button" role="tab" aria-controls="profile"
                    aria-selected="false">{{ __('global.books_for_sale') }}</button>
            </li>
        </ul>
        <div class="tab__container mt--60">
            <div class="product__indicator--5 tab-content w-100">
                <div class="tab-pane fade show active" id="home" role="tabpanel"
                    aria-labelledby="home-tab">
                    <div class="d-flex flex-wrap">
                        @foreach($sample_books as $popular_book)
                            <div class="col-md-3 col-6">
                                    @include('web.partials.book-card', ['book'=>$popular_book])
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel"
                    aria-labelledby="profile-tab">
                    <div class="d-flex flex-wrap">
                        @forelse ($real_books as $popular_book)
                            <div class="col-md-3 col-6">
                                    @include('web.partials.book-card', ['book'=>$popular_book])
                            </div>
                        @empty
                            <h3 class="text-center text-secondary w-100" >
                                {{ __('global.not_avaialable_yet') }}</h3>
                        @endforelse
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('web.books') }}"
                class="btn btn-primary text-white">{{ __('global.show_all') }}</a>
        </div>
    </div>
</section>
@endif

<!-- Books looking for co-authors -->
@if(count($receivable_books))
<section class="wn__bestseller__area bg--white pt--30  pb--80 bg--gray">
    <div class="container">
        <div class="section__title text-center">
            <h2>{!! __('global.books_looking_for_coauthors') !!}</h2>
            <p>{{ __('global.books_looking_coauthors_heading') }}</p>
        </div>
        <div class="tab__container mt--60">
            <div class="product__indicator--5">
                <div class="d-flex flex-wrap">
                    @foreach($receivable_books as $book)
                        <div class="col-md-3 col-6">
                            @include('web.partials.book-card', ['book'=>$book])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('web.books_need_authors') }}"
                class="btn btn-primary text-white">{{ __('global.show_all') }}</a>
        </div>
    </div>
</section>
@endif

<!-- Authors -->
@if(count($authors))
<section class="wn__bestseller__area bg--white pt--30  pb--80">
    <div class="container">
        <div class="section__title text-center">
            <h2>{!! __('global.authors_in_house') !!}</h2>
            <p>{{ __('global.in_house_registered_authors') }}</p>
        </div>
        <div class="tab__container mt--60">
            <!-- Start Single Tab Content -->
            <div class="row single__tab tab-pane fade show active" id="nav-all1" role="tabpanel">
                <!-- Start Single Product -->
                @foreach ($authors as $author)
                <div class="col-md-3 col-6 mt-2">
                    @include('web.partials.author-card')
                </div>
                @endforeach
            </div>
            <!-- End Single Tab Content -->
        </div>
        <div class="mt-5 text-center">
            <a href="{{ route('web.authors') }}"
                class="btn btn-primary text-white">{{ __('global.show_all') }}</a>
        </div>
    </div>
</section>
@endif

<!-- Blogs -->
<section class="wn__recent__post pt--30 pb--30 bg--gray">
    <div class="container">
        <div class="section__title text-center">
            <h2 class="color--theme">{!!__('global.blog_page')!!}</h2>
            <p>{{__('global.recent_posts')}}</p>
        </div>
        <div class="row mt-3 blog-page">
            @foreach($blogs as $post)
            <!-- Start Single Post -->
                <article class="blog__post d-flex flex-wrap col-md-6 col-12">
                    @include('web.partials.post-card')
                </article>
            @endforeach
        </div>
        <div class="mt-5 text-center">
            <a href="{{ route('web.blog_posts') }}"
                class="btn btn-primary text-white">{{ __('global.show_all') }}</a>
        </div>
    </div>
</section>

<!-- Services -->
<section class="best-seel-area bg--white pt--80 pb--80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section__title text-center pb-5">
                    <h2>{!!__('global.specialist_servieces')!!}</h2>
                    <p id="shead">
                        {{ __('global.service_heading') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container account__form m-auto border-0">
        <img src="{{asset('images/web/specialbanner.png')}}" class="w-100">
        <div class="form__btn text-center">
            <a href="consult">{{__('global.request_form')}}</a>
        </div>
    </div>
</section>

<!-- Stay with us -->
<section class="wn__newsletter__area pt--30 pb--80">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 d-flex align-items-center">
                <img src="{{asset('/images/web/stay.png')}}" alt="Illustrated image" class="mx-auto mb-5 mb-lg-0 mr-lg-0 w-75">
            </div>
            <div class="col-lg-7 col-12">
                <div class="section__title text-center">
                    <h2>{!! __('global.stay_with_us') !!}</h2>
                </div>
                <div class="newsletter__block text-center" id="subscribe_form_div">
                    <p>{{ __('global.subscribe_newsletter') }}</p>
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
                    <form action="{{route('web_subscribe')}}" method="POST">
                        @csrf
                        <div class="newsletter__box">
                            <i class="fa-solid fa-user" aria-hidden="true"></i>
                            <input type="text" name="name" placeholder="{{ __('global.enter_name') }}"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="newsletter__box">
                            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                            <input type="email" name="email" placeholder="{{__('global.enter_email')}}" value="{{old('email')}}" required>
                        </div>

                        <div class="newsletter__box d-flex justify-content-between align-items-end flex-wrap mx-auto">
                            <div>
                                {!! htmlFormSnippet() !!}
                            </div>
                            <button type="submit" class="btn btn-secondary">{{__('global.subscribe')}}</button>
                        </div>

                        <div class="single_contact_form newsletter__box form-group d-flex m-auto">
                            <span class="recaptcha-error d-none alert alert-danger mt-2">Recaptcha is required</span>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section("scripts")
<script>
    // Format dates
    document.querySelectorAll(".day").forEach(item => {
        const dateTime = new Date(`${item.textContent}+00:00`);
        const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });

        item.textContent = date;
    })
</script>
@endsection
