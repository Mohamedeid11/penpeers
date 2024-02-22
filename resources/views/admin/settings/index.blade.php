@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.settings')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.settings_edit') }}
                    </h4>
                    <hr>
                    @foreach($setting->form_fields as $key => $field)
                        @php
                            $setting_item = \App\Models\Setting::where(['name'=>$key])->first();
                            $value = $setting_item->value;
                            $label = $setting_item->trans('display_name');
                        @endphp
                        <form class="form form-horizontal ajax-form" method="POST" enctype="multipart/form-data"
                              action="{{route('admin.settings.update', ['setting'=>$setting_item->id])}}">
                            <div class="row">
                                @csrf
                                @method('PUT')

                                <div class="col-10">
                                    <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$setting" :default="$value" :label="$label"/>
                                    <div class="row">
                                        <div class="col-10 offset-2">
                                            <div class="text-danger wrong d-none">
                                                <i class="mdi mdi-sword-cross"></i> <span>INVALID</span>
                                            </div>
                                            <div class="text-success right d-none">
                                                <i class="mdi mdi-check"></i> <span>Saved</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group row justify-content-end">
                                        <input type="submit" class="btn btn-primary" value="{{__('admin.save')}}">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection

