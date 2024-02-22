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
                    <h4 class="m-b-30 m-t-0">{{__('admin.view_book')}}
                        @can('view', $book)
                        <a href="{{route('admin.books.index')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-keyboard-tab"></i></a>
                        @endcan
                    </h4>
                    <hr>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="">
                            <tbody>
                                <tr>
                                    <th >{{__('global.title')}}</th>
                                    <td >{{$book->title}}</td>
                                </tr>
                                <tr>
                                    <th >{{__('global.description')}}</th>
                                    <td >{{Str::limit($book->description, 20)}}</td>
                                </tr>
                                <tr>
                                    <th >{{__('global.author')}}</th>
                                    <td >{{App\Models\BookParticipant::leadAuthor($book->id)->first()->user->name}}</td>
                                </tr>
                                <tr>
                                    <th >{{__('global.genre')}}</th>
                                    <td >{{App\Models\Genre::find($book->genre_id)->name}}</td>
                                </tr>
                                <tr>
                                    <th >{{__('global.slug')}}</th>
                                    <td >{{$book->slug}}</td>
                                </tr>
                                <tr>
                                    <th >{{__('global.popular')}}</th>
                                    <td >
                                        <input type="checkbox" class="smart-toggle" data-toggle="toggle" data-on="{{__('admin.active')}}"
                                            data-off="{{__('admin.not_active')}}" data-onstyle="primary" data-offstyle="secondary"
                                            data-value="{{route('admin.api.books.toggle_popular', ['book_id'=>$book->id])}}" @if($book->popular) checked @endif>
                                    </td>
                                </tr>
                                    <tr>
                                        <th >{{__('global.visibility')}}</th>
                                        <td >{{$book->visibility}}</td>
                                    </tr>

                                <tr>
                                    <th class="align-middle">{{__('global.front_cover')}}</th>
                                    <td>
                                        <img src="{{asset($book->front_cover)}}" alt="" height="100" width="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="align-middle">{{__('global.back_cover')}}</th>
                                    <td >
                                        <img src="{{asset($book->back_cover)}}" alt="" height="100" width="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th >{{__('global.date')}}</th>
                                    <td >{{$book->created_at}}</td>
                                </tr>
                            </tbody>
                        </table>
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
@endsection
