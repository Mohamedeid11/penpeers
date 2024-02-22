@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.faq')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{__('admin.faqs_list')}}
                        @can('create', \App\Models\Faq::class)
                            <a href="{{route('admin.faqs.create')}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\Faq::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.faqs.batch_destroy')}}">
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
                                <th>{{__('global.question')}}</th>
                                <th>{{__('global.answer')}}</th>

                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faqs->items() as $faq)
                                <tr>
                                    <td>
                                        @can('delete', $faq)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$faq->id}}" type="checkbox" value="{{$faq->id}}" data-value="{{$faq->id}}">
                                                <label for="checkbox-{{$faq->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{Str::limit($faq->trans('question'), 15)}}</td>
                                    <td>{{Str::limit($faq->trans('answer'), 35)}}</td>
                                    <td>
                                        {{-- @can('translate', $faq)
                                            <a href="{{route('admin.faqs.translate', ['faq'=>$faq->id])}}" class="btn btn-sm btn-outline-warning"><i class="mdi mdi-translate"></i></a>
                                        @endcan --}}
                                        @can('update', $faq)
                                            <a href="{{route('admin.faqs.edit', ['faq'=>$faq->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $faq)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.faqs.destroy', ['faq'=>$faq->id])}}">
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
                        {!! $faqs->links() !!}
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
