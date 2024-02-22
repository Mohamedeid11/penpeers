@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.users')}}@endsection
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
                <h4 class="m-b-30 m-t-0">{{__('admin.users_list')}}
                    @can('create', \App\Models\User::class)
                    <a href="{{route('admin.users.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                    @endcan
                    @can('delete', \App\Models\User::class)
                    <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.users.batch_destroy')}}">
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
                                <th>{{__('global.name')}}</th>
                                <th>{{__('global.role')}}</th>
                                <th>{{__('global.username')}}</th>
                                <th>{{__('global.email')}}</th>
                                <th>{{__('global.phone')}}</th>
                                <th>{{__('global.image')}}</th>
                                <th>{{__('admin.is_active')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    @can('delete', $user)
                                    <div class="checkbox text-center checkbox-primary">
                                        <input id="checkbox-{{$user->id}}" type="checkbox" value="{{$user->id}}" data-value="{{$user->id}}">
                                        <label for="checkbox-{{$user->id}}">
                                        </label>
                                    </div>
                                    @endcan
                                </td>
                                <td>{{$user->name}}</td>
                                <td>@if($user->role) {{$user->role->trans('display_name')}} @endif</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone}}</td>
                                <td class="text-center">
                                    <img src="{{$user->profile_pic?storage_asset( $user->profile_pic):'https://via.placeholder.com/70x70.png?text=?'}}" class="rounded-circle" height="70" width="70">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="smart-toggle" data-toggle="toggle" data-on="{{__('admin.active')}}" data-off="{{__('admin.not_active')}}" data-onstyle="primary" data-offstyle="secondary" data-value="{{route('admin.api.users.toggle_active', ['user_id'=>$user->id])}}" @if($user->active) checked @endif>
                                </td>
                                <td>
                                    @can('view', $user)
                                    <a href="{{route('admin.users.show', ['user'=>$user->id])}}" class="btn btn-sm btn-outline-warning" title="{{__('admin.user_show')}}">
                                        <i class="mdi mdi-information"></i>
                                    </a>
                                    @endcan
                                    @can('update', $user)
                                    @can('create', \App\Models\Family::class)
                                    @if(! $user->family && $user->isCreator())
                                    <a href="{{route('admin.families.create', ['user'=>$user->id])}}" class="btn btn-sm btn-success"><i class="mdi mdi-plus"></i></a>
                                    @endif
                                    @endcan
                                    @endcan
                                    @can('impersonate', $user)
                                        <form method="POST" class="form d-inline-block" method="POST" action="{{route('admin.users.impersonate', ['user_id'=>$user->id])}}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="{{__('admin.users_impersonate')}}"><i class="fas fa-user-secret"></i></button>
                                        </form>
                                    @endcan                                    @can('update', $user)
                                    <a href="{{route('admin.users.edit', ['user'=>$user->id])}}" class="btn btn-sm btn-info" title="{{__('admin.users_edit')}}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    @endcan
                                    @can('delete', $user)
                                    <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.users.destroy', ['user'=>$user->id])}}">
                                        @csrf
                                        @method('DELETE')
                                        <button href="#" class="btn btn-sm btn-danger" title="{{__('admin.users_delete')}}"><i class="mdi mdi-delete"></i></button>
                                    </form>
                                    @endcan

                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
{{--                <div class="justify-content-center">--}}
{{--                    {!! $users->links() !!}--}}
{{--                </div>--}}
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
