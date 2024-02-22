@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.consults')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.consults_list')}}
                        @can('delete', \App\Models\Consult::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.consults.batch_destroy')}}">
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
                                <th>{{__('global.email')}}</th>
                                <th>{{__('global.phone')}}</th>
                                <th>{{__('admin.occupation')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($consults->items() as $consult)
                                <tr>
                                    <td>
                                        @can('delete', $consult)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$consult->id}}" type="checkbox" value="{{$consult->id}}" data-value="{{$consult->id}}">
                                                <label for="checkbox-{{$consult->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$consult->trans('name')}}</td>
                                    <td>{{$consult->email}}</td>
                                    <td>{{$consult->phone}}</td>
                                    <td>{{$consult->occupation()->first()->trans('name')}}</td>
                                    <td>
                                        @can('delete', $consult)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.consults.destroy', ['consult'=>$consult->id])}}">
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
                        {!! $consults->links() !!}
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
