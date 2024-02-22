@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
@endsection
@section('content')
<main class="main-page">
    @php
    $menu = [[
        'title' => 'Manage '.$book->trans('title'),
        'type' => 'active'
    ]];
    @endphp
    @include('web.partials.book-top-bar', ['menu'=>$menu])
    <section class="section">
        <div class="container-fluid">
            @include('web.partials.book-nav-section')

            <!-- Create chapter -->
            @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                <section class="panel p-2">
                    <header class="panel-heading">
                        <div class="panel-title">
                            @if($edition->chapters->count() == 0)
                                <h2 class="h5">{{ __('global.create_first_chapter') }}</h2>
                            @else
                                <h2 class="h5">{{ __('global.create_new_chapter') }}</h2>
                            @endif
                        </div>
                    </header>

                    <div class="panel-body">
                        <div class="container-fluid">
                            <form class="form" method="POST" action="{{route('web.dashboard.books.editions.chapters.store',
                                    ['book'=>$book->id, 'edition'=>$edition->edition_number])}}">
                                {{@csrf_field()}}
                                <div class="row">
                                    <div class="form-group col-12 col-md-3">
                                        <label class="my-1 mr-2" for="title-input">{{__('global.chapter_title')}}</label>
                                        <input type="text" class="form-control my-1 mr-sm-2 @if($errors->has('name')) is-invalid @endif" id="title-input" value="{{inp_value(null, 'name')}}" name="name">
                                        @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            @foreach($errors->get('name') as $error)
                                            {{$error}}<br>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label class="my-1 mr-2" for="order-input">{{__('global.sequence')}}</label>
                                        <input id="order-input" type="number" step="1" min="1" class="form-control my-1 mr-sm-2 @if($errors->has('order')) is-invalid @endif" value="{{old('order')? inp_value(null, 'order') : ($edition->chapters->count() > 0? $edition->chapters->last()->order+1 : 1)}}" name="order">
                                        @if($errors->has('order'))
                                        <div class="invalid-feedback">
                                            @foreach($errors->get('order') as $error)
                                            {{$error}}<br>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label class="my-1 mr-2" for="author_id-input">{{__('global.assign_author')}}</label>
                                        <select class="form-control my-1 mr-sm-2 @if($errors->has('author_id')) is-invalid @endif" id="author_id-input" name="author_id" required>
                                            <option selected value>{{__('global.choose')}}...</option>
                                            @foreach($book->book_participants()->active()->get() as $book_participant)
                                            <option value="{{$book_participant->user->id}}" {{select_value(null, 'author_id', $book_participant->user->id)}}>
                                                {{$book_participant->user->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="col-12 h7 text-primary">
                                            {{__('global.add_more')}} <a href="{{route('web.dashboard.books.authors.index', ['book'=>$book->id])}}" class="text-underline text-secondary"><i>{{__('global.coauthors')}}</i></a> .
                                        </small>
                                        @if($errors->has('author_id'))
                                        <div class="invalid-feedback">
                                            @foreach($errors->get('author_id') as $error)
                                            {{$error}}<br>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-12 col-md-3">
                                        <label class="my-1 mr-2" for="deadline-input">{{__('global.deadline')}}</label>
                                        <input id="deadline-input" type="date" class="form-control my-1 mr-sm-2 @if($errors->has('deadline')) is-invalid @endif" value="{{inp_value(null, 'deadline')}}" name="deadline" min={{date('Y-m-d')}}>
                                        @if($errors->has('deadline'))
                                        <div class="invalid-feedback">
                                            @foreach($errors->get('deadline') as $error)
                                            {{$error}}<br>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    <button type="submit" id="submit-btn" class="btn btn-primary my-1 mx-auto">{{__('global.save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            @endif

            <!-- All chapters -->
            <section class="panel p-2">
                <header class="panel-heading">
                    <div class="panel-title">
                        <h2 class="h5">{{ __('global.book_chapters') }}</h2>
                    </div>
                </header>
                <div class="panel-body">
                    <table class="table table-bordered chapters-datatable dt-responsive datatable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{__('global.chapters.title')}}</th>
                                <th>{{__('global.sequence')}}</th>
                                <th>{{ __('global.assigned_authors') }}</th>
                                <th>{{__('global.deadline')}}</th>
                                <th>{{__('global.status')}}</th>
                                <th>{{__('global.actions')}}</th>
                            </tr>

                        </thead>
                        <tbody class="text-center line-height-35">
                            @foreach($edition->chapters->sortBy('order') as $book_chapter)
                            <tr>
                                <td></td>
                                <td>
                                    <a class="text-secondary text-underline" href="
                                        {{ route('web.dashboard.books.editions.chapters.edit', ['book'=>$book->id, 'edition'=>$edition->edition_number, 'chapter'=>$book_chapter->id]) }}">{{ $book_chapter->name }}</a>
                                </td>
                                <td>{{$book_chapter->order}}</td>
                                <td>
                                    @foreach($book_chapter->authors as $author)
                                    @php
                                        $valid_author = (! $author->validity && ! $author->free_period_after_expiry)? true : false;
                                    @endphp
                                    <a @if($valid_author) data-title='This author needs to renew subscription plan to continue.' @endif
                                        class="text-secondary text-underline @if($valid_author) text-danger @endif"
                                        href="{{route('web.author_books', ['author' => $author->id, 'type' => 'all_books'])}}"
                                        data-toggle="tooltip"
                                        data-placement="top">
                                        @if($valid_author) <i class="fa-solid fa-triangle-exclamation"></i> @endif
                                        {{ $author->name }}
                                    </a>
                                    @endforeach
                                </td>
                                <td>{{$book_chapter->deadline}}</td>

                                @if($book_chapter->completed)
                                    <td class="text-success"> Completed on : {{$book_chapter->completed_at}} </td>
                                @else
                                    <td> Ongoing </td>
                                @endif

                                <td>
                                    @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                        <form class="d-inline-block delete-chapter-button" method="POST" action="{{route('web.dashboard.books.editions.chapters.destroy', ['book'=>$book->id, 'edition'=>$edition->edition_number, 'chapter'=>$book_chapter->id])}}">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="delete-chapter-button btn btn-danger mx-1 d-inline" title="delete this chapter"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
</main>
@endsection
@section('scripts')
@include('web.partials.datatable')
<script>
    $('body').on('click', '.delete-chapter-button', function(e) {
        e.preventDefault();
        swal({
            title: "Delete this Chapter ?",
            type: 'error',
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "Cancel",
        }, function() {
            $(e.currentTarget).submit();
        });
    });
</script>
@endsection
