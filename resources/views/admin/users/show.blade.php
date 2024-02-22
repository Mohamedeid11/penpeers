@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.users')}}@endsection
@section('head')
@include('admin.inc.datatables-css')
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
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
        <h4 class="m-b-30 m-t-0">{{__('admin.user_show') }}
        </h4>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="m-b-30 m-t-0">{{__('admin.user_details') }}
                    @can('update', $user)
                    <a href="{{route('admin.users.edit', ['user'=>$user->id])}}" class="float-right btn btn-info ml-1" title="{{__('admin.users_edit')}}">
                        <i class="mdi mdi-pencil"></i>
                    </a>
                    @endcan
                    @can('delete', $user)
                    <form method="POST" class="form d-inline-block delete-form float-right" method="POST" action="{{route('admin.users.destroy', ['user'=>$user->id])}}">
                        @csrf
                        @method('DELETE')
                        <button href="#" class="float-right btn btn-danger" title="{{__('admin.users_delete')}}"><i class="mdi mdi-delete"></i></button>
                    </form>
                    @endcan
                </h4>
                <hr>
                <table class="table table-striped table-bordered dt-responsive nowrap">
                    <tbody>
                        <tr>
                            <th>{{__('admin.forms.account_type')}}</th>
                            <td>{{__('global.'.$user->account_type)}}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.country_id')}}</th>
                            <td>{{$user->country?$user->country->trans('name'):''}}</td>
                        </tr>

                        <tr>
                            <th>{{__('admin.forms.username')}}</th>
                            <td>{{$user->username}}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.email')}}</th>
                            <td>{{$user->email}}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.name')}}</th>
                            <td>{{$user->full_name}}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.phone')}}</th>
                            <td>{{$user->phone}}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.nickname')}}</th>
                            <td>{{$user->nickname}}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.company')}}</th>
                            <td>{{$user->company ? $user->company->name: "" }}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.address')}}</th>
                            <td>{{$user->address}}</td>
                        </tr>
                        <tr>
                            <th>{{__('admin.forms.plan_id')}}</th>
                            <td>{{$user->plan ? $user->plan->plan->price : ""}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="m-b-30 m-t-0">{{__('admin.user_hits') }}
                </h4>
                <hr>
                <div id="morris-area-example" style="height: 300px"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="m-b-30 m-t-0">{{__('admin.user_subscription') }}
                </h4>
                <hr>
                <table class="table table-striped table-bordered dt-responsive nowrap">
                    <tbody>
                        <tr>
                            <th>{{__('admin.latest_payment_date')}}</th>
                            <td>
                                @if($user->plan)
                                {{$user->plan->payment->created_at}}
                                @else
                                <span class="text-danger"> {{__('admin.not_existent')}}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('admin.validity')}}</th>
                            <td>
                                @if($user->validity)
                                <span class="text-success">{{__('admin.valid')}}</span>
                                @else
                                <span class="text-danger">{{__('admin.not_valid')}}</span>
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="m-b-30 m-t-0">{{__('admin.user_fake_payment') }}
                </h4>
                <hr>
                <form class="form form-horizontal" method="POST" enctype="multipart/form-data" action="{{route('admin.users.fake_payment', ['user'=>$user->id])}}">
                    @csrf
                    @foreach($payment->form_fields as $key => $field)
                    <x-admin.forms.form-group :field="$field" :name="$key" :item="$payment" />
                    @endforeach
                    <div class="form-group row justify-content-center">
                        <input type="submit" class="btn btn-primary" value="{{__('admin.submit')}}">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-b-30 m-t-0">{{__('admin.user_records') }}

                </h4>
                <hr>

            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('admin.inc.bulkdelete')
@include('admin.inc.smarttoggles')
@include('admin.inc.datatable')
@include('admin.inc.charts')
@endsection
