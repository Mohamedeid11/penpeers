@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.books')}}@endsection
@section('head')
    @include('admin.inc.datatables-css')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('success'))
                        <label class="alert alert-success w-100 alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {!! session()->get('success') !!}
                        </label>
                    @endif
                    @if(Session::has('error'))
                        <label class="alert alert-danger w-100 alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {!! Session::get('error') !!}
                        </label>
                    @endif
                    <h4 class="m-b-30 m-t-0">{{__('admin.books_list')}}
{{--                        @can('create', \App\Models\Book::class)--}}
{{--                            <a href="{{route('admin.books.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>--}}
{{--                        @endcan--}}
                        @can('delete', \App\Models\Book::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.books.batch_destroy')}}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="bulk_delete" value="[]">
                                <button href="#" class="float-right btn btn-danger" type="submit"><i class="mdi mdi-delete"></i> {{__('admin.batch_delete')}}</button>
                            </form>
                        @endcan
                    </h4>
                    <hr>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox text-center checkbox-primary">
                                        <input id="checkbox--1" type="checkbox" value="-1" data-value="-1">
                                        <label for="checkbox--1">
                                        </label>
                                    </div>
                                </th>
                                <th >{{__('global.title')}}</th>
                                <th >{{__('global.description')}}</th>
                                <th >{{__('global.author')}}</th>
                                <th >{{__('global.genre')}}</th>
                                <th >{{__('global.front_cover')}}</th>
                                <th >{{__('global.back_cover')}}</th>
                                <th >{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($books as $i=>$book)
                                <tr>
                                    <td >
                                        @can('delete', $book)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$book->id}}" type="checkbox" value="{{$book->id}}" data-value="{{$book->id}}">
                                                <label for="checkbox-{{$book->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$book->title}}</td>
                                    <td>{{Str::limit($book->description, 20)}}</td>
                                    <td>{{App\Models\BookParticipant::leadAuthor($book->id)->first()->user->name}}</td>
                                    <td>{{App\Models\Genre::find($book->genre_id)->name}}</td>
                                    <td class="text-center">
                                        <img src="{{storage_asset($book->front_cover)}}" alt="" height="50" width="50">
                                    </td>
                                    <td class="text-center">
                                        <img src="{{storage_asset($book->back_cover)}}" alt="" height="50" width="50">
                                    </td>
                                    <td >
{{--                                        @can('viewAny', App\Models\BookEdition::class)--}}
{{--                                            <a href="{{route('admin.book_editions.index', ['book'=>$book->id])}}" class="btn btn-sm btn-info" title="editions"><i class="mdi mdi-book-multiple"></i></a>--}}
{{--                                        @endcan--}}
                                        @can('view', $book)
                                        <a href="{{route('admin.books.show', ['book'=>$book->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>
                                        @endcan
                                        @can('update', $book)
                                            <a href="{{route('admin.books.edit', ['book'=>$book->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $book)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.books.destroy', ['book' => $book->id])}}">
                                                @csrf
                                                @method('DELETE')
                                                <button href="#" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="justify-content-center">
                        {!! $books->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- End Row -->


@endsection
@section('scripts')
    @include('admin.inc.bulkdelete')
    @include('admin.inc.smarttoggles')
    @include('admin.inc.datatable')
    <script>
        $(document).on('submit', '.approve-form', function () {
            event.preventDefault();
            let form = $(this);
            swal(
            {
                title:"Are you sure?",
                text:"Approve this publish request ?",
                type:"success",
                showCancelButton:!0,
                confirmButtonClass:"btn-pimary",
                confirmButtonText:"Yes, Do it!",
                closeOnConfirm: false
            }, function (confirm) {
                if (confirm) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
