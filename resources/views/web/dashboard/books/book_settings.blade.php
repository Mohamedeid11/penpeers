@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
<link rel="stylesheet" href="{{asset('css/web/book-form.css')}}">
<link rel="stylesheet" href="{{ asset('css/web/cropper.min.css') }}">
@endsection
@section('content')
<main class="main-page">
    @php
    $menu = [[
        'title' => 'Manage '.$book->trans('title'),
        'type' => 'active'
    ]];
    @endphp
    @include('web.partials.book-top-bar', ['menu'=>$menu])
    <section class="section">
        <div class="container-fluid">
                @include('web.partials.book-nav-section')

                <!-- edit book details -->
                @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="panel-title">
                                <h2 class="underline h5">{{ __('global.edit_book_details') }}</h2>
                            </div>
                        </header>

                        <div class="panel-body p-20">
                            <div class="container-fluid">
                                <form class="p-20" id="two-column" enctype="multipart/form-data" method="POST" action="{{route('web.dashboard.books.update', ['book'=>$book->id])}}">
                                    @csrf()
                                    @method('PUT')
                                    <div class="row">
                                        <!-- Language -->
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="language-input">{{__('global.choose_language')}}<sup class="color-danger">*</sup></label>
                                                <select class="form-control @if($errors->has('language')) is-invalid @endif" id="language-input" name="language">
                                                    @foreach(locales() as $key => $locale)
                                                        @if($key == 'en' || $key == 'ar')
                                                            <option data-value="{{ lroute($key) }}" value="{{ $key }}"
                                                                @if($key==$book->language) selected
                                                                @endif>{{ $locale['native'] }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if($errors->has('language'))
                                                <div class="invalid-feedback">
                                                    @foreach($errors->get('language') as $error)
                                                    {{$error}}<br>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Title -->
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="title-input">{{__('global.book_title')}}<sup class="color-danger">*</sup></label>
                                                <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title-input" name="title" placeholder="{{__('global.enter_book_title')}}" data-validation="required" required value="{{old('title', $book->title)}}">
                                                @if($errors->has('title'))
                                                <div class="invalid-feedback">
                                                    @foreach($errors->get('title') as $error)
                                                    {{$error}}<br>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description-input">{{__('global.book_description')}}<sup class="color-danger">*</sup></label>
                                                <textarea rows="4" class="form-control @if($errors->has('description')) is-invalid @endif" id="description-input" placeholder="{{__('global.enter_book_description')}}" data-validation="length" data-validation-length="min5" name="description" required> {{old('description', $book->description)}}</textarea>
                                                @if($errors->has('description'))
                                                <div class="invalid-feedback">
                                                    @foreach($errors->get('description') as $error)
                                                    {{$error}}<br>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Front cover -->
                                        <div class="col-12 col-md-6">
                                            @include('web.partials.crop-file-input', ['name'=>'front_cover', 'display_name'=>__('global.front_cover'), 'default_img' => 'defaults/front.png'])
                                        </div>

                                        <!-- Back cover -->
                                        <div class="col-12 col-md-6">
                                            @include('web.partials.crop-file-input', ['name'=>'back_cover', 'display_name'=>__('global.back_cover'), 'default_img' => 'defaults/back.png'])
                                        </div>

                                        <!-- Price -->
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="price-input">{{ __('global.price') . ' ' . __('global.in_USD')}}</label>
                                                <input type="number" class="form-control @if($errors->has('price')) is-invalid @endif" id="price-input" name="price" placeholder="{{__('global.price')}} {{__('global.in_USD')}}" value="{{old('price', $book->price)}}">
                                                @if($errors->has('price'))
                                                    <div class="invalid-feedback">
                                                        @foreach($errors->get('price') as $error)
                                                            {{$error}}<br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Genre -->
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="genre_id-input">{{__('global.choose_genre')}}<sup class="color-danger">*</sup></label>
                                                <select class="form-control @if($errors->has('genre_id')) is-invalid @endif" name="genre_id" required id="genre_id-input">
                                                    <option disabled>{{__('global.select_genre')}}</option>
                                                    @foreach($genres as $genre)
                                                    <option value="{{old('genre_id', $genre->id)}}" {{select_value($book, 'genre_id', $genre->id)}}>{{$genre->trans('name')}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('genre_id'))
                                                <div class="invalid-feedback">
                                                    @foreach($errors->get('genre_id') as $error)
                                                    {{$error}}<br>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($book->completed != 1)
                                            <!-- Enable requests -->
                                            <div class="col-12 col-md-6">
                                                <label for="visibility-input">{{__('global.enable_receive_requests')}}</label>
                                                <div>
                                                    <input type="radio" id="enable-request" hidden name="receive_requests" {{radio_value($book,'receive_requests',1)}} value="true">
                                                    <input type="radio" id="disable-request" hidden name="receive_requests" {{radio_value($book,'receive_requests',0)}}  value="false">
                                                    <div class="acoount-type-check d-flex justify-content-center">
                                                        <label for="enable-request" class="text-white">{{__('global.enable')}}</label>
                                                        <label for="disable-request" class="text-white">{{__('global.disable')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="receive_requests" value="false">
                                        @endif

                                        <!-- Submit -->
                                        <div class="col-12">
                                            <div class="btn-group mt-10 float-right" role="group">
                                                <button type="submit" class="btn bg-black btn-wide "><i class="fa-solid fa-arrow-right"></i> {{__('global.submit')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                @endif

                <!-- Actions -->
                <section class="panel p-2" id="actions">
                    <header class="panel-heading">
                        <div class="panel-title">
                            <h2 class="underline h5">{{__('global.actions')}}</h2>
                        </div>
                    </header>

                    <div class="panel-body">
                        @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                            <div class="row">
                                @if($book->completed())
                                    <!-- Read a book -->
                                    <a class="col-12 col-md-6 mt-2 btn btn-link text-primary px-3 py-0 @if(locale() == 'ar') text-right @else text-left @endif" href="{{route('web.dashboard.preview_book', ['book'=>$book, 'edition'=>$edition->edition_number])}}#edition-content"><i class="fa-solid fa-book"></i> <span class="text-underline">{{__('global.view_this_book')}}</span></a>

                                    <!-- Download a book -->
                                    <button type="submit"
                                        class="col-12 col-md-6 mt-2 btn btn-link text-primary px-3 py-0 @if(locale() == 'ar') text-right @else text-left @endif"
                                        onclick="downloadPDF()"><i class="fa-solid fa-download"></i>
                                        <span class="text-underline">{{ __('global.download_this_book') }}</span></button>
                                    @if($book->status == 1)
                                        <!-- View book -->
                                        <a class="col-12 col-md-6 mt-2 btn btn-link text-primary px-3 py-0 @if(locale() == 'ar') text-right @else text-left @endif"
                                            href="{{ route('web.view_book', ['slug'=>$book->slug, 'type'=>'completed']) }}"><i
                                                class="fa fa-eye"></i>
                                                <span class="text-underline">{{ __('global.view_book_penpeers') }}</span></a>
                                    @endif
                                    <!-- show and hide book -->
                                    <form class="col-12 col-md-6 mt-2" method="get" action="{{route('web.dashboard.books.toggle_visibility_status', ['book'=>$book->id])}}">
                                        @csrf
                                        @if($book->status)
                                        <a type="submit" class="hide-book-button btn btn-link text-danger p-0" ><i class="fa-solid fa-toggle-off"></i> <span class="text-underline">{{__('global.hide_book_penpeers')}}</span></a>
                                        @else
                                        <a type="submit" class="show-book-button btn btn-link text-primary p-0"><i class="fa-solid fa-toggle-on"></i> <span class="text-underline">{{__('global.show_book_penpeers')}}</span></a>
                                        @endif
                                    </form>

                                    <!-- change book status to redit -->
                                    <form class="col-12 col-md-6 mt-2" method="get" action="{{route('web.dashboard.books.redit_book', ['book'=>$book->id])}}">
                                        @csrf
                                        <a type="submit" class="redit-book-button btn btn-link text-danger p-0" title="re-edit book"><i class="fa-solid fa-xmark"></i> <span class="text-underline">{{__('global.reedit_book')}}</span></a>
                                    </form>
                                @else(!$book->completed())
                                    <!-- Complete the book -->
                                    <form method="get" class="col-12 col-md-6" action="{{route('web.dashboard.books.complete_book', ['book'=>$book->id])}}">
                                        @csrf
                                        <a type="submit" class="complete-book-button btn btn-link text-primary p-0" title="Complete this book"><i class="fa-solid fa-square-check"></i> <span class="text-underline">{{__('global.complete_book')}}</span></a>
                                    </form>
                                @endif
                            </div>
                        @else
                            @if($book->completed())
                                <div class="row">
                                    <!-- Read the book -->
                                    <a class="col-12 col-md-6 mt-2 btn btn-link text-primary px-3 py-0 @if(locale() == 'ar') text-right @else text-left @endif"
                                        href="{{ route('web.dashboard.preview_book', ['book'=>$book, 'edition'=>$edition->edition_number]) }}#edition-content"><i
                                            class="fa fa-book"></i>
                                            <span class="text-underline">{{ __('global.view_this_book') }}</span></a>
                                    <!-- Download the book -->
                                    <button type="submit"
                                        class="col-12 col-md-6 mt-2 btn btn-link text-primary px-3 py-0 @if(locale() == 'ar') text-right @else text-left @endif"
                                        onclick="downloadPDF()"><i class="fa-solid fa-download"></i>
                                        <span class="text-underline">{{ __('global.download_this_book') }}</span></button>
                                    @if($book->status == 1)
                                        <!-- View book on PenPeers -->
                                        <a class="col-12 col-md-6 mt-2 btn btn-link text-primary px-3 py-0 @if(locale() == 'ar') text-right @else text-left @endif"
                                            href="{{ route('web.view_book', ['slug'=>$book->slug, 'type'=>'completed']) }}"><i
                                                class="fa fa-eye"></i>
                                                <span class="text-underline">{{ __('global.view_book_penpeers') }}</span></a>
                                    @endif
                                </div>
                            @else
                                <p class="text-center">{{__('global.no_actions')}}</p>
                            @endif
                        @endif
                    </div>
                </section>

                <!-- Danger zone -->
                @if($book->lead_author->id == auth()->id() || $book->deleted_at)
                <section class="panel text-dark p-2">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h5 class="underline mt-n">{{ __('global.danger_zone') }}</h5>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="container-fluid">
                            <div class="row">
                                @if($book->deleted_at)
                                    <p class="w-100 deleted_at_time"></p>
                                    @if($book->lead_author->id == auth()->id())
                                        <form method="post" id="restore-book"
                                            action="{{ route('web.dashboard.books.cancel_delete_book', ['book'=>$book->id]) }}">
                                            @method('get')
                                            @csrf

                                            <div class="row">
                                                <button class="btn text-danger btn-link">
                                                    <i class="fa-solid fa-trash-can-arrow-up"></i>
                                                    <span
                                                        class="text-underline">{{ __('global.restore_book') }}</span>
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                @else
                                <form method="post" id="delete-book" action="{{route('web.dashboard.books.destroy', ['book'=>$book->id])}}">
                                    @method('delete')
                                    @csrf

                                    <div class="row">
                                        <a class="btn text-danger btn-link" href="#"><i class="fa-solid fa-trash-can"></i>
                                            <span class="text-underline">{{__('global.delete_book')}}</span></a>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
                @endif
        </div>
    </section>
</main>
@endsection
@section('scripts')
@include('web.partials.crop-file-modal',['name'=>'front_cover'])
@include('web.partials.crop-file-modal',['name'=>'back_cover'])
@include('web.partials.crop-file-scripts')
<script>
    $('body').on('click', '.complete-book-button', function (e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.complete_this_book') }}",
            type: "success",
            confirmButtonClass: "btn-primary",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{ __('global.ok') }}",
        }, function () {
            $(e.currentTarget.parentElement).submit();
        });
    });

    $('body').on('click', '.hide-book-button', function (e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.hide_this_book') }}",
            type: "error",
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "{{ __('global.cancel') }}",
            confirmButtonText: "{{ __('global.ok') }}",
        }, function () {
            $(e.currentTarget.parentElement).submit();
        });
    });

    $('body').on('click', '.redit-book-button', function (e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.reedit_this_book') }}",
            type: "warning",
            confirmButtonClass: "btn-secondary",
            showCancelButton: true,
            cancelButtonText: "{{ __('global.cancel') }}",
            confirmButtonText: "{{ __('global.ok') }}",
        }, function () {
            $(e.currentTarget.parentElement).submit();
        });
    });

    $('body').on('click', '.show-book-button', function (e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.show_this_book') }}",
            type: "success",
            confirmButtonClass: "btn-primary",
            showCancelButton: true,
            cancelButtonText: "{{ __('global.cancel') }}",
            confirmButtonText: "{{ __('global.ok') }}",
        }, function () {
            $(e.currentTarget.parentElement).submit();
        });
    });

    $('body').on('click', '#delete-book', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.delete_this_book') }}",
            type: 'error',
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "{{ __('global.cancel') }}",
            confirmButtonText: "{{ __('global.ok') }}",
        }, function() {
            $('#delete-book').submit();
        });
    });

    const deletedAtTime = document.querySelector(".deleted_at_time");
    if(deletedAtTime) {
        const date = new Date("{{ $book->deleted_at }}").
        toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" })
        deletedAtTime.innerHTML = `{{__('global.book_deleted_at')}}${date}`
    }
</script>
@endsection
