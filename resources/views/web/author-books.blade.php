@extends("web.layouts.master")
@section('heads')
<link href="{{ asset('css/web/books.css') }}" rel="stylesheet" />
<link href="{{ asset('css/web/author.css') }}" rel="stylesheet" />
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--6', 'title' => '<span class="color--theme">'.$author->name.'</span> '.__('global.profile')])
@section('content')
    @if($author->is_public)
        <main class="row author-profile align-items-baseline">
            <section class="author-aside">
                <img class="avatar rounded-circle" src="{{storage_asset($author->profile_pic)}}" alt="{{$author->name}}">

                <h2 class="mt-4 mx-3 author-aside__name text-center">{{$author->name}}</h2>
                <nav class="bradcaump-content">
                    <a class="breadcrumb_item" href="{{route('web.get_landing')}}">{{__('global.home')}}</a>
                    <span class="brd-separetor">/</span>
                    <span class="breadcrumb_item active">{{$author->name}}</span>
                </nav>

                <!-- Author data -->
                <div class="author-aside__data mt-5">
                    <p class="mb-2"><i class="fa-solid fa-envelope" aria-hidden="true"></i> {{$author->email}}</p>
                    @if($author->country->name)
                        <p class="mb-2"><i class="fa-solid fa-map-marker" aria-hidden="true"></i> {{$author->country->name}}</p>
                    @endif
                    @if($author->mobile_number)
                        <p class="mb-2"><i class="fa-solid fa-mobile" aria-hidden="true"></i>
                            {{ $author->mobile_number }}</p>
                    @endif
                    @if(isset($author->social_links) && isset($author->social_links['whatsapp']))
                        <p class="mb-2"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> {{$author->social_links['whatsapp']}}</p>
                    @endif
                    @if(count($author->interests) > 0)
                        <p class="mb-2"><i class="fa-solid fa-heart" aria-hidden="true"></i>
                        @php
                            $names = [];
                            foreach($author->interests as $index => $interest) {
                                $names[$index] = $interest->name;
                            }
                        @endphp
                            {{implode(", ", $names)}}
                        </p>
                    @endif
                    @if($author->bio)
                        <p class="mb-2"><i class="fa-solid fa-file-text" aria-hidden="true"></i> {{$author->bio}}</p>
                    @endif
                </div>
                <!-- Social links -->
                @if(isset($author->social_links))
                    <div class="author-aside__social d-flex my-5 mx-auto">
                        @if(isset($author->social_links['twitter']) && str_contains($author->social_links['twitter'], 'twitter.com'))
                        <a href="{{$author->social_links['twitter']}}" target="_blank"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a>
                        @endif
                        @if(isset($author->social_links['facebook']) && str_contains($author->social_links['facebook'], 'facebook.com'))
                        <a href="{{ $author->social_links['facebook'] }}" target="_blank"><i
                                class="fa-brands fa-facebook" aria-hidden="true"></i></a>
                        @endif
                        @if(isset($author->social_links['linkedin']) && str_contains($author->social_links['linkedin'], 'linkedin.com'))
                        <a href="{{ $author->social_links['linkedin'] }}" target="_blank"><i
                                class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if(isset($author->social_links['youtube']) && str_contains($author->social_links['youtube'], 'youtube.com'))
                        <a href="{{ $author->social_links['youtube'] }}" target="_blank"><i
                                class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(isset($author->social_links['instagram']) && str_contains($author->social_links['instagram'], 'instagram.com'))
                        <a href="{{ $author->social_links['instagram'] }}" target="_blank"><i
                                class="fa-brands fa-instagram"></i></a>
                        @endif
                    </div>
                @endif
            </section>

            <section class="col-lg col-12 author-books" id="author-books">
                <!-- Books nav -->
                <ul class="nav nav-tabs" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{route('web.author_books', ['author'=>$author->id, 'type'=> 'all_books'])}}" class="nav-link @if($type =='all_books') active @endif">{{__('global.all_books')}}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{route('web.author_books', ['author'=>$author->id, 'type'=> 'lead_books'])}}" class="nav-link @if($type =='lead_books') active @endif">{{__('global.lead_book')}}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{route('web.author_books', ['author'=>$author->id, 'type'=> 'co_books'])}}" class="nav-link @if($type =='co_books') active @endif">{{__('global.co_book')}}</a>
                    </li>
                </ul>

                <!-- Books -->
                <div class="tab-content">
                    @if(count($books) > 0)
                        <div class="row w-100">
                            @foreach ($books as $book)
                                <div class="col-xl-3 col-lg-4 col-6 my-3">
                                    @include('web.partials.book-card')
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 mx-auto d-flex justify-content-center">
                            {{$books->links()}}
                        </div>
                    @else
                        <div class="col">
                            <p class="text-center my-5 text-muted">{{ __('global.no_books') }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </main>
    @else
        <section class="row justify-content-center mt-4">
            <p class="text-muted h5">{{ __('global.author_not_available') }}</p>
        </section>
    @endif
@endsection
