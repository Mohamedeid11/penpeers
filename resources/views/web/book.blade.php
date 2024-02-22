@extends('web.layouts.master')
@section('heads')
    <meta property="og:site_name" content="{{$book->title}}" />
    <link rel="stylesheet" href="{{ asset('css/web/books.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/web/book.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/web/likely.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/web/likely-custom.css') }}" />
@endsection
@php
    // dd($book->participants->pluck('id')->toArray());
@endphp
@include('web.partials.page-title', ['background' => 'bg-image--5', 'title' => $book->trans('title'), 'sub_title' => $book->trans('title')])
@section('content')
    <!-- Alerts -->
    @include('web.partials.flashes')
    @error('rate')
    <span class="alert alert-danger w-100 alert-dismissible fade show" role="alert">
        {{$message}}
    </span>
    @enderror
    @error('review')
    <span class="alert alert-danger w-100 alert-dismissible fade show" role="alert">
        {{$message}}
    </span>
    @enderror

    <div class="maincontent bg--white pt--80 pb--55">
        <div class="container">
            <div class="row">
                <!-- Aside -->
                @include('web.partials.categories-widget', ['book_genre' => $book->genre->id])

                <!-- Book details -->
                <main class="col-lg-9 col-12 order-1 order-lg-2 wn__single__product">
                    <section class="row position-relative">
                        <!-- Images slider -->
                        <div class="col-lg-6 col-12">
                            <div class="wn__fotorama__wrapper">
                                <div class="fotorama wn__fotorama__action" data-nav="thumbs" data-arrows="false" data-click="false" data-allowfullscreen="true">
                                    <img class="book-img" src="{{storage_asset($book->front_cover)}}" alt="Front cover">
                                    <img class="book-img" src="{{storage_asset($book->back_cover)}}" alt="Back cover">
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="col-lg-6 col-12 product__info__main">
                            <div class="d-flex justify-content-between flex-wrap align-items-center">
                                <h2>{{$book->trans('title')}}</h2>
                            </div>

                            <!-- Review -->
                            <div class="product-reviews-summary rate-percent">
                                @include('web.partials.rate', ['rate' => $book->rate])
                            </div>

                            <!-- Authors -->
                            <div class="mt-3 dropdown">
                                <a class="dropdown-toggle text-underline text-secondary" href="#"
                                    role="button"
                                data-toggle="dropdown" aria-expanded="false">
                                    {{ __('global.authors_book') }}
                                </a>
                                <div class="dropdown-menu">
                                    @foreach ($book->participants as $participant)
                                        <span class="posted_in d-block">
                                            <a href="{{route('web.author_books', ['author' => $participant->id, 'type' => 'all_books'])}}"
                                                class="text-underline text-secondary dropdown-item">
                                                {{$participant->name}}
                                            </a>
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Genre -->
                            <span class="posted_in d-block mt-1">{{__('global.genre')}}:
                                @if($type == 'lookingForCoAuthor' )
                                    <a href="{{route('web.books_need_authors', ['genre'=>$book->genre->id])}}" class="text-underline text-secondary">{{$book->genre->trans('name')}}</a>
                                @else
                                    <a href="{{ route('web.books', ['genre'=>$book->genre->id]) }}"
                                        class="text-underline text-secondary">{{ $book->genre->trans('name') }}</a>
                                @endif
                            </span>

                            @if($book->completed == 1)
                                @if ($book->price)
                                    <span class="posted_in d-block mt-1">{{__('global.price')}}:
                                        <span class="book_price">{{$book->price}}$</span>
                                    </span>

                                    <a class="btn btn btn-primary mt-4"
                                    href="{{ route('web.buy_book_request',['slug'=>$book->slug ]) }}"
                                    role="button">{{ __('global.request_to_buy') }}
                                    </a>
                                @endif
                                @if($book->sample)
                                    <a class="btn btn btn-outline-primary mt-4"
                                        href="{{ asset('samples/'.$book->slug.'.pdf') }}" role="button"
                                        target="_blank">{{ __('global.book_sample') }}
                                    </a>
                                @endif
                            @endif
                            <!-- Request to join button -->
                            @if($type == "lookingForCoAuthor" && !in_array( auth()->id() ,$book->book_participants->pluck('user_id')->toArray() ))
                                <a class="btn btn-secondary mt-4"
                                    href="{{ route('web.get_book_request',['slug'=>$book->slug ]) }}"
                                    role="button">{{__('global.participate')}}</a>
                            @endif

                            <!-- Sharing -->
                            @php
                                $shareUrl = LaravelLocalization::getNonLocalizedURL(route('web.view_book', ['slug'=> $book->slug , 'type'=> $type]));
                                $bookTitle = $book->title;
                           @endphp
                            <div class='likely text-center section_tittle pt-4 w-100 d-flex flex-wrap justify-content-center' data-url="{{ $shareUrl }}">
                                <div class='facebook' data-url="{{ $shareUrl }}" title="{{__('global.share_facebook')}}"></div>
                                <div class='twitter' data-url="{{ $shareUrl }}"
                                    title="{{ __('global.share_twitter') }}"></div>
                                <div class='linkedin' data-url="{{ $shareUrl }}"
                                    title="{{ __('global.share_linkedin') }}"></div>
                                <div class='telegram' data-url="{{ $shareUrl }}"
                                    title="{{ __('global.share_telegram') }}"></div>
                                <div class='whatsapp' data-url="{{ $shareUrl }}"
                                    title="{{ __('global.share_whatsapp') }}"></div>
                                {{-- <div class='likely__widget' title="{{ __('global.share_email') }}">
                                    <button class="likely__button btn-clip likely-icon" data-toggle="modal"
                                        data-target="#share-to-email-modal" id="add-edition-trigger"
                                        data-placement="top" type="button">
                                        <img class='likely__icon likely-icon'
                                            alt="{{ __('global.share_email') }}"
                                            src='<?=asset("images/img/mail.png")?>'>
                                    </button>
                                </div> --}}
                                <div class='likely__widget likely-copy'
                                    title="{{ __('global.copy_url') }}">
                                    <button class="likely__button btn-clip likely-icon"
                                        onclick="copyShareURL(this, '{{ $shareUrl }}')" data-toggle="tooltip"
                                        data-placement="top" type="button">
                                        <img class='likely__icon likely-icon'
                                            alt="{{ __('global.copy_url') }}"
                                            src='<?=asset("images/img/copy-icon.svg")?>'>
                                    </button>
                                </div>
                                <span class="likely__widget border-0" style="width: 20px">{{__('global.or')}}</span>
                                <div class='likely__widget likely-mob'
                                    title="{{ __('global.mobile_only') }}">
                                    <a class='likely__button answer-example-share-button text-white likely-icon'
                                        href='javascript:;' data-url="{{ $shareUrl }}">
                                        <img class='likely__icon likely-icon'
                                            alt="{{ __('global.mobile_only') }}"
                                            src='<?=asset("images/img/mobile.png")?>'>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Summary and Reviews -->
                    <section class="product__info__detailed">
                        <nav class="pro_details_nav nav justify-content-start" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#nav-details"
                                role="tab">{{__('global.summary')}}
                            </a>
                            @if($book->status ==1 && $book->completed() == 1)
                                <a class="nav-item nav-link show-reviews" data-toggle="tab" href="#nav-review" role="tab">{{ __('global.reviews') }}</a>
                            @endif
                        </nav>

                        <div>
                            <!-- Summary -->
                            <div class="pro__tab_label tab-pane fade show active" id="nav-details" role="tabpanel">
                                <div class="description__attribute">
                                    <p>{{$book->description}}</p>
                                </div>
                            </div>

                            <!-- Reviews -->
                            <div class="pro__tab_label tab-pane fade " id="nav-review" role="tabpanel">
                                @if( $book->status ==1 && $book->completed() == 1)
                                <div class="review__attribute">
                                    <h2>{{ __('global.authors_reviews') }}</h2>
                                    <div class="carousel-container">
                                        <div class="owl-carousel owl-theme">
                                            @foreach($reviews as $review)
                                            <div class="card review-items">
                                                <div class="card-body review-box">
                                                    <p class="font-weight-bold"><a href="{{route('web.author_books', ['author' => $review->user->id, 'type' => 'all_books'])}}">{{$review->user->name}}</a></p>
                                                    <p class="small blog-date">{{$review->created_at}}</p>
                                                    @include('web.partials.rate', ['rate' => $review->rate])
                                                    <p class="card-text review-txt mt-3">{{$review->review}}</p>
                                                    <button class="btn text-secondary p-0 ml-auto d-block mt-1 font-weight-bold" onclick="toggleMoreText(this)">{{__('global.read_more')}}</button>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                @endif

                                @php
                                    $check_book_authors = in_array(auth()->id() ,$book->participants->pluck('id')->toArray());
                                @endphp
                                <!-- Add review -->
                                @if(Auth::check() && $book->status == 1 && $book->completed() == 1 && ! $check_book_authors)
                                <form class="review-fieldset" id="submit-review" action="{{route('submit_book_review', ['slug'=> $book->slug])}}" method="POST">
                                    @csrf
                                    <h2 class="mb-4">{{ __('global.you_are_reviewing') }}
                                        {{ $book->trans('title') }} </h2>
                                    <div class="review-field-ratings">
                                        <div class="review_form_field">
                                            <div class="input__box d-flex align-items-center">
                                                <span>{{__('global.rate')}}</span>
                                                <input hidden name="rate" class="form-control"/>
                                                <ul class="rating d-flex ml-3">
                                                    <li>
                                                        <button type="button" onclick="setRate(1)">
                                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" onclick="setRate(2)">
                                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" onclick="setRate(3)">
                                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" onclick="setRate(4)">
                                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" onclick="setRate(5)">
                                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                        </button>
                                                    </li>
                                                </ul>
                                                <p class="invalid-feedback">Rate is required</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="review_form_field">
                                        <div class="input__box">
                                            <span>{{__('global.review')}}</span>
                                            <textarea name="review" class="p-2" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox ">
                                                <input id="confirm_reading" class="custom-control-input @if($errors->has('confirm_reading')) is-invalid @endif" name="confirm_reading" value="read" type="checkbox" required>
                                                <label for="confirm_reading" class="custom-control-label">
                                                    {{ __('global.confirm_reading_conditions') }}
                                                </label>
                                                @if($errors->has('confirm_reading'))
                                                    <div class="invalid-feedback">
                                                        @foreach($errors->get('confirm_reading') as $error)
                                                            <p>{{$error}}</p>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="review-form-actions">
                                            <button>{{__('global.submit')}} {{__('global.rate')}}</button>
                                        </div>
                                    </div>


                                </form>
                                @endif
                            </div>
                        </div>
                    </section>
                    <div class="wn__related__product pt--80 pb-5">
                        <div class="section__title text-center">
                            <h2>{!!__('global.related_books')!!}</h2>
                        </div>
                        <div class="row mt--60">
                            @foreach ($related as $book)
                                <!-- Start Single Product -->
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                    @include('web.partials.book-card')
                                </div>
                                <!-- End Single Product -->
                            @endforeach
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
@include('web.partials.sharing_link_to_email_modal')
@section('scripts')
<script src="{{ asset('js/web/likely.js') }}"></script>
<script>
    // Copy URL
    const copyShareURL = (e, text) => {
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        e.title="{{ __('global.copied') }}";
        $(e).tooltip("show");
    }

    // Share mobile
    $(document).on('click','.answer-example-share-button', function() {
        const url = $(this).attr('data-url');
        if (navigator.share) {
            navigator.share({
                title: '{{$bookTitle}} PenPeers Book',
                text: 'Have a look at {{$bookTitle}} PenPeers Book',
                url: url,
            })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing', error));
        } else {
            console.log('Share not supported on this browser, do it the old way.');
        }
    });

    // Set rate number
    const setRate = (starsNum) => {
        const list = document.querySelector("form.review-fieldset .rating");

        for(let i=0; i<5; i++) {
            list.children[i].querySelector("svg").dataset.prefix = i < starsNum ? "far" : "fas";
            list.children[i].querySelector("svg").dataset.prefix = i < starsNum ? "fas" : "far";
        }

        document.querySelector("form.review-fieldset input[name='rate']").value = starsNum;
    }

    // Validation before submit review
    document.querySelector("#submit-review")?.addEventListener("submit", (e) => {
        e.preventDefault();
        const rate = e.target.rate.value
        if(!rate)
            return e.target.rate.classList.add("is-invalid")
        else
            return e.target.submit();
    })

    // Reviews carousel
    $('.owl-carousel').owlCarousel({
        nav:true,
        margin: 16,
        rtl: lang === "ar" ? true : false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
        }
    });

    // Read more / Read less toggle
    const toggleMoreText = (e) => {
        const pElm = e.previousElementSibling;

        if(e.innerHTML === "{{__('global.read_more')}}") {
            e.innerHTML = "{{__('global.read_less')}}";
            pElm.classList.remove("review-txt");
        } else {
            e.innerHTML = "{{__('global.read_more')}}";
            pElm.classList.add("review-txt");
        }
    }

    const toggleMoreBtn = () => {
        document.querySelectorAll(".card-text").forEach(elm => {
            const btn = elm.nextElementSibling;
            const btnClasses = btn.classList;

            console.log(btn, elm.clientHeight, elm.scrollHeight)

            if(btnClasses.contains('d-block') && elm.clientHeight >= elm.scrollHeight) {
                btnClasses.add('d-none');
                btnClasses.remove('d-block');
            } else if(btnClasses.contains('d-none') && elm.clientHeight < elm.scrollHeight) {
                btnClasses.remove('d-none');
                btnClasses.add('d-block');
            }
        })
    }

    window.addEventListener("resize", toggleMoreBtn);
    $(".show-reviews").on("shown.bs.tab", toggleMoreBtn);

    // Zooming images
    function imageZoom(img, result) {
        var lens, cx, cy;

        /*create lens:*/
        lens = document.createElement("DIV");
        lens.setAttribute("class", "img-zoom-lens");
        lens.style.opacity = 0;
        lens.style.zIndex = -1;
        /*insert lens:*/
        img.parentElement.insertBefore(lens, img);

        /*execute a function when someone moves the cursor over the image, or the lens:*/
        img.addEventListener("mouseenter", mouseEnter);
        img.addEventListener("mousemove", moveLens);
        img.addEventListener("mouseout", removeImg);

        function mouseEnter(e) {
            if(!document.querySelectorAll(".fotorama--fullscreen .fotorama__img").length || window.innerWidth < 900) return
            if(!result) {
                result = document.createElement("div");
                result.classList.add("col-lg-6", "col-12", "position-absolute", "img-zoom-result", "h-100", "overlay");
                /*calculate the ratio between result DIV and lens:*/
                cx = 200 / lens.offsetWidth;
                cy = 200 / lens.offsetHeight;
                img.parentElement.parentElement.append(result);
            }

            lens.style.opacity = 1;
            lens.style.zIndex = 10;

            if(!result.classList.contains("active")) result.classList.add("active");
            result.style.backgroundImage = "url('" + img.src + "')";
        }

        function moveLens(e) {
            if(!document.querySelectorAll(".fotorama--fullscreen .fotorama__img").length || window.innerWidth < 900)
                return
            var pos, x, y;

            /*get the cursor's x and y positions:*/
            pos = getCursorPos(e);
            /*calculate the position of the lens:*/
            x = pos.x - (lens.offsetWidth / 2);
            y = pos.y - (lens.offsetHeight / 2);
            /*prevent the lens from being positioned outside the image:*/
            if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
            if (x < 0) {x = 0;}
            if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
            if (y < 0) {y = 0;}
            /*set the position of the lens:*/
            lens.style.left = x + "px";
            lens.style.top = y + "px";
            /*display what the lens "sees":*/
            result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
            /*set background properties for the result DIV:*/
            result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
        }

        function getCursorPos(e) {
            var a, x = 0, y = 0;
            e = e || window.event;
            /*get the x and y positions of the image:*/
            a = img.getBoundingClientRect();
            /*calculate the cursor's x and y coordinates, relative to the image:*/
            x = e.pageX - a.left;
            y = e.pageY - a.top;
            /*consider any page scrolling:*/
            x = x - window.pageXOffset;
            y = y - window.pageYOffset;
            return {x : x, y : y};
        }

        function removeImg(e) {
            if(!document.querySelectorAll(".fotorama--fullscreen .fotorama__img").length) return
            if(result) {
                result.classList.remove("active");
                result.style.backgroundImage = "initial";
            }
            lens.style.opacity = 0;
            lens.style.zIndex = -1;
        }
    }

    window.addEventListener("load", () => {
        document.querySelectorAll(".fotorama__img").forEach((img) => imageZoom(img, document.querySelector(".img-zoom-result")))
    })

    // Format dates
    document.querySelectorAll(".blog-date").forEach(item => {
        const dateTime = new Date(`${item.textContent}+00:00`);
        const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
        const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" })

        item.textContent = `${date}, ${time}`;
    })
</script>
@endsection
