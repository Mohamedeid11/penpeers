@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.newsletter_emails')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.newsletter_emails_list')}}
                        @can('create', \App\Models\NewsletterEmail::class)
                            <a href="{{route('admin.newsletter_emails.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.send_email')}}</a>
                        @endcan
                        @can('delete', \App\Models\NewsletterEmail::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.newsletter_emails.batch_destroy')}}">
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
                                <th>{{__('global.subject')}}</th>
                                <th>{{__('global.content')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($newsletter_emails->items() as $newsletter_email)
                                <tr>
                                    <td>
                                        @can('delete', $newsletter_email)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$newsletter_email->id}}" type="checkbox" value="{{$newsletter_email->id}}" data-value="{{$newsletter_email->id}}">
                                                <label for="checkbox-{{$newsletter_email->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{$newsletter_email->subject}}</td>
                                    <td>{{Str::limit($newsletter_email->content, 35)}}</td>
                                    <td>
                                        @can('view', $newsletter_email)
                                            <a href="{{route('admin.newsletter_emails.show', ['newsletter_email'=>$newsletter_email->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>
                                        @endcan
                                        @can('delete', $newsletter_email)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.newsletter_emails.destroy', ['newsletter_email'=>$newsletter_email->id])}}">
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
                        {!! $newsletter_emails->links() !!}
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
