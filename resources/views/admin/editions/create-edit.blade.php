@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.editions')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$book_edition->exists?__('admin.editions_edit'):__('admin.editions_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$book_edition->exists?route('admin.book_editions.update', ['book'=> request('book'), 'book_edition'=>$book_edition->id]):route('admin.book_editions.store', ['book'=> request('book')])}}">
                        @csrf
                        @if($book_edition->exists)
                            @method('PUT')
                        @endif
                        @foreach($book_edition->form_fields as $key => $field)
                            <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$book_edition"/>
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
