@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.social_links')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.social_links_list')}}
                        @can('create', \App\Models\SocialLink::class)
                            <a href="{{route('admin.social_links.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\SocialLink::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.social_links.batch_destroy')}}">
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
                                <th>{{__('global.link')}}</th>
                                <th>{{__('global.icon')}}</th>
                                <th>{{__('admin.is_active')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($social_links->items() as $social_link)
                                <tr>
                                    <td>
                                        @can('delete', $social_link)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$social_link->id}}" type="checkbox" value="{{$social_link->id}}" data-value="{{$social_link->id}}">
                                                <label for="checkbox-{{$social_link->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$social_link->trans('name')}}</td>
                                    <td><a href="{{$social_link->link}}">{{$social_link->link}}</a></td>
                                    <td>{{$social_link->icon}}</td>
                                    <td class="text-center">
                                        <input type="checkbox" class="smart-toggle" data-toggle="toggle" data-on="{{__('admin.active')}}"
                                               data-off="{{__('admin.not_active')}}" data-onstyle="primary" data-offstyle="secondary"
                                               data-value="{{route('admin.api.social_links.toggle_active', ['social_link_id'=>$social_link->id])}}" @if($social_link->active) checked @endif>
                                    </td>
                                    <td>
                                        {{-- @can('translate', $social_link)
                                            <a href="{{route('admin.social_links.translate', ['social_link'=>$social_link->id])}}" class="btn btn-sm btn-outline-warning"><i class="mdi mdi-translate"></i></a>
                                        @endcan --}}
                                         @can('update', $social_link)
                                            <a href="{{route('admin.social_links.edit', ['social_link'=>$social_link->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $social_link)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.social_links.destroy', ['social_link'=>$social_link->id])}}">
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
                        {!! $social_links->links() !!}
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
