@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.contact_messages')}}@endsection
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
                        <h4 class="m-b-30 m-t-0">{{__('admin.contact_messages_list')}}
                            <a href="{{route('admin.contact_messages.index')}}" class="float-right btn btn-primary ml-1"><i class="mdi mdi-arrow-left"></i> {{__('admin.go_back')}}</a>
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>
                                        {{__('global.first_name')}}
                                    </th>
                                    <td>
                                        {{$contact_message->first_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{__('global.last_name')}}
                                    </th>
                                    <td>
                                        {{$contact_message->last_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{__('global.email')}}
                                    </th>
                                    <td>
                                        {{$contact_message->email}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{__('global.message')}}
                                    </th>
                                    <td>
                                        {{$contact_message->message}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{__('global.created_at')}}
                                    </th>
                                    <td>
                                        {{$contact_message->created_at}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        @can('delete', $contact_message)
                            <div class="w-100 text-center">
                                <br>
                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.contact_messages.destroy', ['contact_message'=>$contact_message->id])}}">
                                @csrf
                                @method('DELETE')
                                <button href="#" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i> {{__('admin.delete')}}</button>
                            </form>
                            </div>
                        @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.inc.bulkdelete')
@endsection
