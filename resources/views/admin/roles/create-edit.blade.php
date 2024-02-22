@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.roles')}}@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-30 m-t-0">{{$role->exists?__('admin.roles_edit'):__('admin.roles_add') }}
                    </h4>
                    <hr>
                    <form class="form form-horizontal" method="POST" enctype="multipart/form-data"
                          action="{{$role->exists?route('admin.roles.update', ['role'=>$role->id]):route('admin.roles.store')}}">
                        @csrf
                        @if($role->exists)
                            @method('PUT')
                        @endif
                        @foreach($role->form_fields as $key => $field)
                            <x-admin.forms.admin-form-group :field="$field" :name="$key" :item="$role"/>
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
    <script>
        $(document).on('change', 'select[name=guard]', function () {
            let guard = $(this).val();
            $('.guards').addClass('d-none');
            $('.guards.guard-'+guard).removeClass('d-none');
            $("input[type=checkbox][name='permissions[]']").prop('checked', false);
        })
    </script>
@endsection
