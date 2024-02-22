@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.blog')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.posts_list')}}
                        @can('create', \App\Models\Blog::class)
                            <a href="{{route('admin.blogs.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\Blog::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.blogs.batch_destroy')}}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="bulk_delete" value="[]">
                                <button href="#" class="float-right btn btn-danger" type="submit"><i class="mdi mdi-delete"></i> {{__('admin.batch_delete')}}</button>
                            </form>
                        @endcan
                    </h4>
                    <hr>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox text-center checkbox-primary">
                                        <input id="checkbox--1" type="checkbox" value="-1" data-value="-1">
                                        <label for="checkbox--1">
                                        </label>
                                    </div>
                                </th>
                                <th>{{__('global.user')}}</th>
                                <th>{{__('global.title')}}</th>
                                <th>{{__('global.description')}}</th>
{{--                                <th>{{__('global.approved')}}</th>--}}
                                <th>{{__('global.image')}}</th>
                                <th>{{__('global.date')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($blogs->items() as $blog)
                                <tr>
                                    <td>
                                        @can('delete', $blog)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$blog->id}}" type="checkbox" value="{{$blog->id}}" data-value="{{$blog->id}}">
                                                <label for="checkbox-{{$blog->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$blog->user()->first()->name}}</td>
                                    <td>{{$blog->trans('title')}}</td>
                                    <td>{{Str::limit($blog->trans('description'), 50)}}</td>
{{--                                    <td>{{$blog->approved? 'approved':'pending'}}</td>--}}
                                    <td class="text-center">
                                        <img src="{{$blog->image?asset('storage/'.$blog->image):'https://via.placeholder.com/70x70.png?text=?'}}" class="rounded-circle" height="70" width="70">
                                    </td>
                                    <td>{{$blog->created_at}}</td>
                                    <td>
                                        @can('update', $blog)
                                            <a href="{{route('admin.blogs.edit', ['blog'=>$blog->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $blog)
                                            <form method="POST" class="form d-inline-block delete-form" action="{{route('admin.blogs.destroy', ['blog'=>$blog->id])}}">
                                                @csrf
                                                @method('DELETE')
                                                <button href="#" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        @endcan
{{--                                        @if (! $blog->approved)--}}
{{--                                            @can('approve', $blog)--}}
{{--                                                <form method="GET" class="form d-inline-block approve-form"action="{{route('admin.blogs.approve_blog', ['blog'=>$blog->id])}}">--}}
{{--                                                    @csrf--}}
{{--                                                    <button href="#" class="btn btn-sm btn-primary"><i class="mdi mdi-approval"></i></button>--}}
{{--                                                </form>--}}
{{--                                            @endcan--}}
{{--                                        @endif--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="justify-content-center">
                        {!! $blogs->links() !!}
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
        $(document).ready(function(){
            $(document).on('submit', '.bulk-delete-form,.approve-form', function () {
                event.preventDefault();
                let form = $(this);
                swal(
                    {
                        title:"Are you sure?",
                        type:"success",
                        showCancelButton:!0,
                        confirmButtonClass:"btn-primary",
                        confirmButtonText:"Yes, Do it!",
                        closeOnConfirm: false
                    }, function (confirm) {
                        if (confirm) {
                            form.submit();
                        }
                    }
                );
            });
        })
    </script>
@endsection
