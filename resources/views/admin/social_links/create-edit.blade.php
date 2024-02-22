@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.social_links')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$social_link->exists?__('admin.social_links_edit'):__('admin.social_links_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$social_link->exists?route('admin.social_links.update', ['social_link'=>$social_link->id]):route('admin.social_links.store')}}">
                        @csrf
                        @if($social_link->exists)
                            @method('PUT')
                        @endif
                        @foreach($social_link->form_fields as $key => $field)
                            <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$social_link"/>
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
