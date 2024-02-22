@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.login_attempts')}}@endsection
@section('head')
@include('admin.inc.datatables-css')
<style>
    td {
        vertical-align: middle !important;
    }
</style>
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
                <hr>
                <div class="table-responsive ">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{__('admin.forms.username')}}</th>
                                <th>{{__('admin.forms.password')}}</th>
                                <th>{{__('admin.info')}}</th>
                                <th>{{__('admin.request_at')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loginAttempts as $user)
                            <tr>
                                <td>{{$user->username}}</td>
                                <td>{{$user->password}}</td>
                                <td>{{$user->info}}</td>
                                <td>{{$user->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="justify-content-center">
                    {!! $loginAttempts->links() !!}
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
