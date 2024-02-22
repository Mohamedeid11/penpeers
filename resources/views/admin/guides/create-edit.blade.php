@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.guide')}}@endsection
@section('head')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/ckeditor/styles.css')}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$guide->exists?__('admin.guide_edit'):__('admin.guide_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$guide->exists?route('admin.guides.update', ['guide'=>$guide->id]):route('admin.guides.store')}}">
                        @csrf
                        @if($guide->exists)
                            @method('PUT')
                        @endif
                        @foreach($guide->form_fields as $key => $field)
                            <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$guide"/>
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