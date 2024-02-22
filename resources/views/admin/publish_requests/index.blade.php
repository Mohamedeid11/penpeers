@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.publish_requests')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.publish_requests_list')}}
                        @can('delete', \App\Models\BookPublishRequest::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.publish_requests.batch_destroy')}}">
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
                                <th>{{__('global.book')}}</th>
                                <th>{{__('global.author')}}</th>
                                <th>{{__('global.edition')}}</th>
                                <th>{{__('global.publication_date')}}</th>
                                <th>{{__('global.status')}}</th>
                                <th>{{__('global.date')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($publishRequests->items() as $i=>$request)
                                <tr>
                                    <td>
                                        @can('delete', $request)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$request->id}}" type="checkbox" value="{{$request->id}}" data-value="{{$request->id}}">
                                                <label for="checkbox-{{$request->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$request->book()->first()->title}}</td>
                                    <td>{{App\Models\User::find($request->user_id)->name}}</td>
                                    <td>{{ordinal(App\Models\BookEdition::find($request->book_edition_id)->edition_number)}} Edition</td>
                                    <td>{{$request->publication_date}}</td>
                                    <td>{{$request->approved == 1? 'Approved' : 'pending'}}</td>
                                    <td>{{$request->created_at}}</td>
                                    <td>
                                        @if ($request->approved == 0)
                                            <form method="GET" class="form d-inline-block approve-form" method="POST" action="{{route('admin.publish_requests.approve_request', ['publish_request' => $request->id])}}">
                                                @csrf
                                                <button href="#" class="btn btn-sm btn-primary"><i class="mdi mdi-approval"></i></button>
                                            </form>
                                        @endif
                                        @can('delete', $request)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.publish_requests.destroy', ['publish_request' => $request->id])}}">
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
                        {!! $publishRequests->links() !!}
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
