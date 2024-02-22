@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
<link rel="stylesheet" href="{{asset('css/web/dashboard-books.css')}}" />
@endsection
@section('content')
<div class="main-page">
    @include('web.partials.dashboard-header', ['title' => __('global.my_books'), 'sub_title' => __('global.my_books_sub_heading'), 'current' => '<li class="active">'.__('global.my_books').'</li>'])
    @include('web.partials.flashes')

    <div class="section">
        <div class="container-fluid">
            <div class="row">
                @if($title == "My Books List")
                    <div class="col-md-12 d-flex mb-4">
                        <a class="btn btn-lg btn-primary ml-auto rounded-0"
                            href="{{ route('web.dashboard.books.create') }}">
                            <i class="fa-solid fa-square-plus"></i> {{ __('global.create_book') }}</a>
                    </div>
                @endif
                <section class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h2 class="h5">{{$title}}</h2>
                            </div>
                        </div>
                        <div class="panel-body p-20">
                            <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__('global.book_title')}}</th>
                                        <th>{{ __('global.my_role') }}</th>
                                        <th>{{__('global.book_status')}}</th>
                                        <th>{{ __('global.visibility_status') }}</th>
                                    </tr>

                                </thead>
                                <tbody>
                                @if(count($books) > 0)
                                    @foreach($books as $book)
                                        <tr>
                                            <td></td>
                                            <td><a class="text-underline text-secondary" href="{{route('web.dashboard.books.authors.index', ['book'=>$book->id])}}">{{$book->title}}</a></td>
                                            <td>
                                                @foreach($book->roles as $role)
                                                    {{$role->display_name}}
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                @if($book->deleted_at)
                                                    <a class="text-underline text-danger"
                                                    href="{{ route('web.dashboard.books.editions.edition_settings', ['book'=>$book->id, 'edition'=>1]) . "#actions" }}">{{ __('global.deleting') }}</a>
                                                @else
                                                    @if($book->completed == 1)
                                                        <a class="text-underline text-secondary"
                                                            href="{{ route('web.dashboard.books.editions.edition_settings', ['book'=>$book->id, 'edition'=>1]) . "#actions" }}">{{ __('global.completed_on') }}
                                                            {{ $book->editing_status_changed_at }}</a>
                                                    @elseif ($book->completed == 2)
                                                        <a class="text-underline text-secondary"
                                                            href="{{ route('web.dashboard.books.editions.edition_settings', ['book'=>$book->id, 'edition'=>1]) . "#actions" }}">{{ __('global.reediting_on') }}
                                                            {{ $book->editing_status_changed_at }}</a>
                                                    @else
                                                        <a class="text-underline text-secondary" href="{{route('web.dashboard.books.editions.edition_settings', ['book'=>$book->id, 'edition'=>1]) . "#actions"}}">{{__('global.ongoing')}}</a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($book->status_changed_at)
                                                    @if ($book->status)
                                                        <span>{{ __('global.shown_on') }}
                                                            {{ $book->status_changed_at }}</span>
                                                    @else
                                                        <span>{{ __('global.hidden_on') }}
                                                            {{ $book->status_changed_at }}</span>
                                                    @endif
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</div>

@endsection
@section('scripts')
@include('web.partials.datatable')
<script>
    // Bigger image
    let bookCoverImgs = document.querySelectorAll(".book-cover img");

    bookCoverImgs.forEach(img => {
        img.addEventListener("mouseenter", () => {
            const biggerImage = document.createElement("img");
            biggerImage.src = img.src;
            biggerImage.classList.add("book-cover-bigger-img");
            img.parentElement.appendChild(biggerImage);
        });

        img.addEventListener("mouseleave", () => {
            const biggerImage = document.querySelector(".book-cover-bigger-img");
            if(biggerImage)
                biggerImage.parentElement.removeChild(biggerImage);
        });
    })
</script>

@endsection
