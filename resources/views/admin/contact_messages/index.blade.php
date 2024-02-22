@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.contact_messages')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.contact_messages_list')}}
                        {{-- @can('create', \App\Models\ContactMessage::class)
                            <a href="{{route('admin.contact_messages.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan --}}
                        @can('delete', \App\Models\ContactMessage::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.contact_messages.batch_destroy')}}">
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
                                <th>{{__('global.first_name')}}</th>
                                <th>{{__('global.last_name')}}</th>
                                <th>{{__('global.email')}}</th>
                                <th>{{__('global.message')}}</th>
                                <th>{{__('admin.is_read')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contact_messages->items() as $contact_message)
                                <tr>
                                    <td>
                                        @can('delete', $contact_message)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$contact_message->id}}" type="checkbox" value="{{$contact_message->id}}" data-value="{{$contact_message->id}}">
                                                <label for="checkbox-{{$contact_message->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$contact_message->first_name}}</td>
                                    <td>{{$contact_message->last_name}}</td>
                                    <td>{{$contact_message->email}}</td>
                                    <td>{{Str::limit($contact_message->message, 50)}}</td>
                                    <td>
                                        {{$contact_message->read ? 'YES' : 'NO'}}
                                    </td>
                                    <td>
                                        {{-- @can('update', $contact_message)
                                            <a href="{{route('admin.contact_messages.edit', ['contact_message'=>$contact_message->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan --}}
                                        @can('view', $contact_message)
                                            <a href="{{route('admin.contact_messages.show', ['contact_message'=>$contact_message->id])}}" class="btn btn-sm btn-warning"><i class="mdi mdi-eye"></i></a>
                                        @endcan

                                        @can('delete', $contact_message)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.contact_messages.destroy', ['contact_message'=>$contact_message->id])}}">
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
                        {!! $contact_messages->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- End Row -->


@endsection
@section('scripts')
    @include('admin.inc.bulkdelete')
    @include('admin.inc.smarttoggles')
    @include('admin.inc.datatable', ['order'=>4])
@endsection
