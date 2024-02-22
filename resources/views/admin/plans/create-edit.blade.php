@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.plans')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$plan->exists?__('admin.plans_edit'):__('admin.plans_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$plan->exists?route('admin.plans.update', ['plan'=>$plan->id]):route('admin.plans.store')}}">
                        @csrf
                        @if($plan->exists)
                            @method('PUT')
                        @endif
                        @foreach($plan->form_fields as $key => $field)
                            <x-admin.forms.form-group :field="$field" :name="$key" :item="$plan"/>
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
