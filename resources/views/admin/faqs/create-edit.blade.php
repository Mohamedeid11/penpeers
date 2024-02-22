@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.faq')}}@endsection
@section('head')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/ckeditor/styles.css')}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$faq->exists?__('admin.faq_edit'):__('admin.faq_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$faq->exists?route('admin.faqs.update', ['faq'=>$faq->id]):route('admin.faqs.store')}}">
                        @csrf
                        @if($faq->exists)
                            @method('PUT')
                        @endif
                        @foreach($faq->form_fields as $key => $field)
                            <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$faq"/>
                        @endforeach
                        <div class="form-group row justify-content-center">
                            <input type="submit" class="btn btn-primary" value="{{__('admin.submit')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.inc.ckeditor-scripts')
@endsection
