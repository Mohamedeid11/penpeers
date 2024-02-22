@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.plans')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.plans_list')}}
                        @can('create', \App\Models\Plan::class)
                            <a href="{{route('admin.plans.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\Plan::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.plans.batch_destroy')}}">
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
                                <th>{{__('global.account_type')}}</th>
                                <th>{{__('global.name')}}</th>
                                <th>{{__('global.period')}}</th>
                                <th>{{__('global.price')}}</th>
                                <th>{{__('admin.is_active')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($plans->items() as $plan)
                                <tr>
                                    <td>
                                        @if(! $plan->is_system)

                                        @can('delete', $plan)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$plan->id}}" type="checkbox" value="{{$plan->id}}" data-value="{{$plan->id}}">
                                                <label for="checkbox-{{$plan->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                        @else
                                            <div class="text-center text-danger"><p class="text-uppercase">System</p></div>
                                        @endif
                                    </td>
                                    <td>{{__('global.'.$plan->account_type)}}</td>
                                    <td>{{$plan->name}}</td>
                                    <td>{{__('global.'.$plan->period)}}</td>
                                    <td>{{$plan->price}}</td>
                                    <td class="text-center">
                                        <input type="checkbox" class="smart-toggle" data-toggle="toggle" data-on="{{__('admin.active')}}"
                                               data-off="{{__('admin.not_active')}}" data-onstyle="primary" data-offstyle="secondary"
                                               data-value="{{route('admin.api.plans.toggle_active', ['plan_id'=>$plan->id])}}" @if($plan->active) checked @endif>
                                    </td>
                                    <td>
                                        @can('translate', $plan)
                                            <a href="{{route('admin.plans.translate', ['plan'=>$plan->id])}}" class="btn btn-sm btn-outline-warning"><i class="mdi mdi-translate"></i></a>
                                        @endcan
                                        @can('update', $plan)
                                            <a href="{{route('admin.plans.edit', ['plan'=>$plan->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $plan)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.plans.destroy', ['plan'=>$plan->id])}}">
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
                        {!! $plans->links() !!}
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
