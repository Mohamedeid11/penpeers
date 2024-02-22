@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.newsletter_emails')}}@endsection
@section('head')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/ckeditor/styles.css')}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$newsletter_email->exists?__('admin.newsletter_emails_show'):__('admin.newsletter_emails_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$newsletter_email->exists?'':route('admin.newsletter_emails.store')}}">
                        @csrf
                        @foreach($newsletter_email->form_fields as $key => $field)
                            <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$newsletter_email"/>
                        @endforeach
                        @if(! $newsletter_email->exists)
                        <div class="form-group row justify-content-center">
                            <input type="submit" class="btn btn-primary" value="{{__('admin.submit')}}">
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.inc.ckeditor-scripts')
@endsection
