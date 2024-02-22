@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.countries')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$country->exists?__('admin.countries_edit'):__('admin.countries_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$country->exists?route('admin.countries.update', ['country'=>$country->id]):route('admin.countries.store')}}">
                        @csrf
                        @if($country->exists)
                            @method('PUT')
                        @endif
                        @foreach($country->form_fields as $key => $field)
                            <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$country"/>
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
