@extends("web.layouts.master")
@section('heads')
<link rel="stylesheet" href="{{ asset('css/web/books.css') }}" />
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--5', 'title' => __('global.books_looking_coauthors'), 'sub_title' => __('global.books_looking_coauthors')])
@section('content')
<div class="page-shop-sidebar left--sidebar bg--white section-padding--lg">
    <div class="container">
        <div class="row">

            <!-- Book Genres -->
            @include('web.partials.categories-widget', ['type' => 'lookingForCoAuthor', 'book_genre' => request()->genre])

            <!-- Books -->
            <div class="col-lg-9 col-12 order-1 order-lg-2">
                <!-- Header -->
                <header class="shop__list__wrapper d-flex flex-wrap flex-md-nowrap justify-content-between">
                    <p>@if($books->count() > 0) {{ __('global.showing') }}
                        1–{{ $books->count() < 12? $books->count() : 12 }} {{ __('global.of') }}
                        {{ $books->total() }} {{ __('global.results') }} @else
                        {{ __('global.showing') }} 0 {{ __('global.results') }}
                        @endif </p>
                </header>

                <!-- All books -->
                <section class="shop-grid tab-pane fade show active" id="nav-grid" role="tabpanel">
                    <div class="row">
                        @foreach($books as $book)
                            <!-- Start Single Product -->
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                @include('web.partials.book-card')
                            </div>
                            <!-- End Single Product -->
                        @endforeach
                    </div>

                    <!-- Paging -->
                    <div class="row justify-content-center mt-3">
                        {{ $books->links() }}
                    </div>

                </section>
            </div>
        </div>
    </div>
</div>
@endsection
