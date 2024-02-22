@extends("web.layouts.master")
@section('heads')
<link rel="stylesheet" href="{{asset('css/web/books.css')}}" />
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--5', 'title' => __('global.books'), 'sub_title' => __('global.books')])
@section('content')
<div class="page-shop-sidebar left--sidebar bg--white section-padding--lg">
	<div class="container">
		<div class="row">
			<!-- Book Generes -->
			@include('web.partials.categories-widget', ['type' => 'all', 'book_genre' => request()->genre])

			<!-- Books -->
			<main class="col-lg-9 col-12 order-1 order-lg-2">
				<header>
					<h3 class="mb-3 wedget__title border-bottom-0 font-weight-bold">
                        <a href="{{ route('web.books_need_authors') }}" style="text-decoration: underline;">
                            {!! __('global.books_looking_coauthors') !!}
						</a>
					</h3>

					<div class="shop__list__wrapper d-flex justify-content-between">
						<p>@if($books->count() > 0) {{ __('global.showing') }}
							1–{{ $books->count() < 12? $books->count() : 12 }} {{__('global.of')}}
							{{ $books->total() }} {{ __('global.results') }} @else
							{{ __('global.showing') }} 0 {{ __('global.results') }} @endif </p>

                        <form action="{{ route('web.books', ['genre' => request()->genre, 'filter_type' => request()->filter_type]) }}" id="filter-form">
                            <select class="form-control w-auto" name="filter_type" id="filter_type">
                                {{-- <option disabled selected value="">Filter by:</option> --}}
                                <option value="samples" {{ request()->filter_type != 'for_sale'? 'selected' : ''}}>{{__('global.sample_books')}}</option>
                                <option value="for_sale" {{ request()->filter_type == 'for_sale'? 'selected' : ''}}>{{__('global.books_for_sale')}}</option>
                            </select>
                        </form>
					</div>
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
			</main>
		</div>
	</div>
</div>
@endsection

@section('scripts')

    <script>

        $(document).ready(function(){

            $(document).on('change', '#filter_type', function(){
                $('#filter-form').submit();
            });

        });

    </script>

@endsection
