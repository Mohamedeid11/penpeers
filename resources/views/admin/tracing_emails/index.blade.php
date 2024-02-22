@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('admin.tracing_emails.title')}}@endsection
@section('head')
@include('admin.inc.datatables-css')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{__('admin.tracing_emails.list')}} </h4>
                    <hr>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('global.subject')}}</th>
                                <th>{{__('global.book')}}</th>
                                <th>{{__('global.sender_name')}}</th>
                                <th>{{__('global.receiver_name')}}</th>
                                <th>{{__('global.date')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tracing_emails as $one)
                                <tr>
                                    <td>{{$one->id}}</td>
                                    <td>{{$one->subject}}</td>
                                    <td>{{isset($one->book) ? $one->book->title : ' - '}}</td>
                                    <td>{{isset($one->sender) ? $one->sender->name : ' - '}}</td>
                                    <td>{{isset($one->receiver) ? $one->receiver->name : ' - '}}</td>
                                    <td>{{$one->created_at}}</td>
                                </tr>
                            @endforeach
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
