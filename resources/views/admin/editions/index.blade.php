@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.editions')}}@endsection
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
                    <h4 class="m-b-30 m-t-0">{{request('book')->title . " " . __('admin.editions_list')}}
                        @can('create', \App\Models\BookEdition::class)
                            <a href="{{route('admin.book_editions.create', ['book' => request('book')])}}" class="float-right btn btn-success ml-1"><i class="mdi mdi-plus"></i> {{__('admin.add_new')}}</a>
                        @endcan
                        @can('delete', \App\Models\BookEdition::class)
                            <form method="POST" class="form float-right bulk-delete-form" method="POST" action="{{route('admin.book_editions.batch_destroy', ['book' => request('book')])}}">
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
                                <th>{{__('admin.forms.edition_number')}}</th>
                                <th>{{__('global.publication_date')}}</th>
                                <th>{{__('admin.forms.visibility')}}</th>
                                <th>{{__('admin.forms.original_price')}}</th>
                                <th>{{__('admin.forms.discount_price')}}</th>
                                <th>{{__('admin.forms.date')}}</th>
                                <th>{{__('admin.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($editions->items() as $edition)
                                <tr>
                                    <td>
                                        @can('delete', $edition)
                                            <div class="checkbox text-center checkbox-primary">
                                                <input id="checkbox-{{$edition->id}}" type="checkbox" value="{{$edition->id}}" data-value="{{$edition->id}}">
                                                <label for="checkbox-{{$edition->id}}">
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                    <td>{{ordinal($edition->edition_number)}} Edition</td>
                                    <td>{{$edition->status_changed_at? $edition->status_changed_at : "-"}}</td>
                                    <td>{{$edition->visibility}}</td>
                                    <td>{{$edition->original_price}}</td>
                                    <td>{{$edition->discount_price}}</td>
                                    <td>{{$edition->created_at}}</td>
                                    <td>
                                        @can('update', $edition)
                                            <a href="{{route('admin.book_editions.edit', ['book'=> request('book'),'book_edition'=>$edition->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $edition)
                                            <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.book_editions.destroy', ['book'=> request('book'), 'book_edition'=>$edition->id])}}">
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
                        {!! $editions->links() !!}
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
