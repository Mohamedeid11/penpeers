@extends('web.layouts.dashboard')
@section('heads')
    <link rel="stylesheet" href="{{asset('css/web/cropper.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/web/book-form.css')}}">
    <link rel="stylesheet" href="{{ asset('css/web/cropper.min.css') }}">
@endsection
@section('content')
    <main class="main-page">
        @include('web.partials.dashboard-header', ['title' => __('global.my_books'), 'sub_title' => __('global.my_books_sub_heading'), 'current' => '<li class="active">'.__('global.create_new_book').'</li>'])

        <section class="section">
            @include('web.partials.flashes')
            <div class="container-fluid">
                <div class="panel">
                    <header class="panel-heading">
                        <div class="panel-title">
                            <h2 class="underline h5">{{__('global.create_book')}}</h2>
                        </div>
                    </header>

                    <div class="panel-body">
                        <form id="two-column" enctype="multipart/form-data" method="POST" action="{{route('web.dashboard.books.store')}}">
                            {{@csrf_field()}}
                            <div class="row">
                                <!-- Language -->
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="language-input">{{ __('global.choose_language') }}<sup class="color-danger">*</sup></label>
                                        <select class="form-control @if($errors->has('language')) is-invalid @endif" id="language-input" name="language" required>
                                            @foreach(locales() as $key => $locale)
                                                @if($key == 'en' || $key == 'ar')
                                                <option data-value="{{lroute($key)}}" value="{{$key}}" @if($key == locale()) selected @endif>{{$locale['native']}}</option>
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
                                        <label for="title-input">{{ __('global.book_title') }} <sup
                                                class="color-danger">*</sup></label>
                                        <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title-input" name="title"
                                                placeholder="{{__('global.enter_book_title')}}" data-validation="required" required>
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
                                        <label
                                            for="description-input">{{ __('global.book_description') }}
                                            <sup class="color-danger">*</sup></label>
                                        <textarea class="form-control @if($errors->has('description')) is-invalid @endif" id="description-input"
                                                placeholder="{{ __('global.enter_book_description') }}"
                                                data-validation="length"
                                                data-validation-length="min5" name="description" required></textarea>
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
                                        <input type="number" class="form-control @if($errors->has('price')) is-invalid @endif" id="price-input" name="price" min="0"
                                               placeholder="{{__('global.price')}} {{__('global.in_USD')}}">
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
                                        <label for="genre_id-input">{{ __('global.choose_genre') }}
                                            <sup class="color-danger">*</sup></label>
                                        <select class="form-control @if($errors->has('genre_id')) is-invalid @endif" name="genre_id" required
                                            id="genre_id-input">
                                            <option>{{ __('global.select_genre') }}</option>
                                            @foreach($genres as $genre)
                                                <option value="{{ $genre->id }}">{{ $genre->trans('name') }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('genre_id'))
                                            <div class="invalid-feedback">
                                                @foreach($errors->get('genre_id') as $error)
                                                    {{ $error }}<br>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Recieve requests -->
                                <div class="col-12 col-md-6">
                                    <label
                                        for="visibility-input">{{ __('global.enable_receive_requests') }}<sup
                                            class="color-danger">*</sup></label>
                                    <div>
                                        <input type="radio" id="enable-request" hidden name="receive_requests" checked value="true">
                                        <input type="radio" id="disable-request" hidden name="receive_requests" value="false">
                                        <div class="acoount-type-check d-flex justify-content-center">
                                            <label for="enable-request" class="text-white">{{__('global.enable')}}</label>
                                            <label for="disable-request" class="text-white">{{__('global.disable')}}</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <button type="submit" class="btn bg-black btn-wide ml-auto d-block"><i class="fa-solid fa-arrow-right"></i> {{__('global.create')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('scripts')
    @include('web.partials.crop-file-modal',['name'=>'front_cover'])
    @include('web.partials.crop-file-modal',['name'=>'back_cover'])
    @include('web.partials.crop-file-scripts')
@endsection
