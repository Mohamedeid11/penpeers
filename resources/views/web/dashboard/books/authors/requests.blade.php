@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
@endsection
@section('content')
<div class="main-page">
    @php
    $menu = [[
        'title' => 'Manage '.$book->trans('title'),
        'type' => 'active'
    ]];
    @endphp
    @include('web.partials.book-top-bar', ['menu'=>$menu])
    <section class="section">
        <header class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('web.partials.book-nav-section')
                </div>
                <div class="col-md-12 mt-4">
                    @include('web.partials.authors-nav-section')
                </div>
            </div>
            <div class="col-md-12 ">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-title d-flex">
                            <h2 class="h5">{{ __('global.book_join_requests') }}</h2>
                        </div>
                    </div>
                    <div class="panel-body p-20">
                        <div class="container-fluid">
                            <div class="row">
                                <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ __('global.author') }}</th>
                                            <th>{{ __('global.email') }}</th>
                                            <th>{{ __('global.message') }}</th>
                                            <th>{{ __('global.bio') }}</th>
                                            <th>{{ __('global.sent_at') }}</th>
                                            <th>{{ __('global.accepted_at') }}</th>
                                            @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                                <th>{{ __('global.actions') }}</th>
                                            @endif
                                        </tr>

                                    </thead>
                                    <tbody class="line-height-35">
                                        @foreach($book->requests->sortByDesc('created_at') as $book_request)
                                            <tr>
                                                <td></td>
                                                @if($book_request->user_id)
                                                    <td class="dt-control">{{$book_request->user->name}}</td>
                                                    <td>{{$book_request->user->email}}</td>
                                                @else
                                                    <td>{{$book_request->name}}</td>
                                                    <td>{{$book_request->email}}</td>
                                                @endif
                                                <td>{{ $book_request->message }}</td>
                                                <td>{{ $book_request->bio }}</td>
                                                <td>
                                                    <span class="blog-date">
                                                        {{$book_request->created_at}}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($book_request->accepted_at)
                                                    <span class="blog-date">
                                                        {{$book_request->accepted_at}}
                                                    </span>
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                                    <td class="d-flex flex-wrap" style="gap: 4px">
                                                        @if(!$book_request->accepted_at)
                                                        <form class="d-inline-block accept-request" method="POST" action="{{route('web.accept_book_request', [ 'book'=>$book_request->book ,'request'=>$book_request->id])}}">
                                                            @csrf
                                                            <button class="btn btn-primary text-white" type="submit" title="Accept"><i class="fa-solid fa-check"></i></button>
                                                        </form>

                                                        <form class="d-inline-block delete-request" method="POST" action="{{route('web.delete_book_request', [  'book'=>$book_request->book , 'request'=>$book_request->id])}}">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-danger text-white" type="submit" title="Reject And Delete"><i class="fa-solid fa-trash-can"></i></button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </section>
</div>

@endsection
@section('scripts')
@include('web.partials.datatable')
<script>
    $('body').on('click', '.accept-request', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.accept_request') }}",
            type: 'success',
            confirmButtonClass: "btn-primary",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{ __('global.ok') }}"
        }, function() {
            $(e.currentTarget).submit();
        });
    });

    $('body').on('click', '.delete-request', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.delete_request') }}",
            type: 'error',
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "{{ __('global.cancel') }}",
            confirmButtonText: "{{ __('global.ok') }}"
        }, function() {
            $(e.currentTarget).submit();
        });
    });

    // Format dates
    document.querySelectorAll(".blog-date").forEach(item => {
        const dateTime = new Date(`${item.textContent}+00:00`);
        const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
        const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });

        item.textContent = `${date}, ${time}`;
    })

    $('.table tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = datatable.row(tr);
        if (row.child.isShown()) {
            const elms = Array.from($(tr).next().find(".blog-date"));

            elms.forEach(elm => {
                const dateTime = new Date(`${elm.innerHTML}+00:00`);
                const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
                const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });
    
                elm.innerHTML = `${date}, ${time}`;
            })
        }
    });
</script>
@endsection
