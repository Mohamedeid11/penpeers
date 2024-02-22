@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.countries')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.countries_list')}}
                        @can('create', \App\Models\Country::class)
                            <a href="{{route('admin.countries.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\Country::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.countries.batch_destroy')}}">
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
                                <th>{{__('global.nationality')}}</th>
                                <th>{{__('global.key')}}</th>
                                <th>{{__('global.image')}}</th>
                                <th>{{__('admin.is_active')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($countries as $country)
                                <tr>
                                    <td>
                                        @can('delete', $country)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$country->id}}" type="checkbox" value="{{$country->id}}" data-value="{{$country->id}}">
                                                <label for="checkbox-{{$country->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$country->trans('name')}}</td>
                                    <td>{{$country->trans('nationality')}}</td>
                                    <td>{{$country->key}}</td>
                                    <td class="text-center">
                                        <img src="{{$country->image?asset('storage/'.$country->image):'https://via.placeholder.com/70x70.png?text=?'}}" class="rounded-circle" height="70" width="70">
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="smart-toggle" data-toggle="toggle" data-on="{{__('admin.active')}}"
                                               data-off="{{__('admin.not_active')}}" data-onstyle="primary" data-offstyle="secondary"
                                               data-value="{{route('admin.api.countries.toggle_active', ['country_id'=>$country->id])}}" @if($country->active) checked @endif>
                                    </td>
                                    <td>
                                        @can('update', $country)
                                            <a href="{{route('admin.countries.edit', ['country'=>$country->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $country)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.countries.destroy', ['country'=>$country->id])}}">
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
{{--                    <div class="justify-content-center">--}}
{{--                        {!! $countries->links() !!}--}}
{{--                    </div>--}}
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
