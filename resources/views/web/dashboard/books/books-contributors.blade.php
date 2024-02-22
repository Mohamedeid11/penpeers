@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
<link rel="stylesheet" href="{{asset('css/web/dashboard-books.css')}}" />
@endsection
@section('content')
<main class="main-page">
    @include('web.partials.dashboard-header', ['title' => __('global.coauthor_participation'), 'sub_title' => __('global.coauthor_participation_subheading'), 'current' => '<li class="active">'.__('global.coauthor_participation').'</li>'])
    @include('web.partials.flashes')

    <section class="section mt-4">
        <div class="container-fluid">
            <div class="panel">
                <header class="panel-heading">
                    <div class="panel-title">
                        <h2 class="h5">{{__('global.coauthor_participation')}}</h2>
                    </div>
                </header>

                <div class="panel-body p-20">
                    <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{__('global.authors')}}</th>
                                <th>{{__('global.role')}}</th>
                                <th>{{__('global.book')}}</th>
                                <th>{{__('global.assigned_chapters')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contributors as $book_participant)
                            <tr>
                                <td></td>
                                <td>
                                    <a class="text-secondary text-underline" href="{{route('web.author_books', ['author' => $book_participant->user->id, 'type' => 'all_books'])}}">
                                        {{$book_participant->user->name}}
                                    </a>
                                </td>
                                <td>
                                    {{$book_participant->book_role->trans('display_name')}}
                                </td>
                                <td>
                                    <a class="text-secondary text-underline" href="{{route('web.dashboard.books.authors.index', ['book'=>$book_participant->book->id])}}">
                                        {{$book_participant->book->title}}
                                    </a>
                                </td>
                                <td>
                                    @foreach($book_participant->book_special_chapters as $book_special_chapter)
                                        <a  class="text-secondary text-underline" href="{{route('web.dashboard.books.editions.special_chapters.edit', ['book' => $book_participant->book_id , 'edition' => $book_special_chapter->book_edition->edition_number , 'special_chapter' => $book_special_chapter->id ])}}">
                                            {{$book_special_chapter->special_chapter->trans('display_name')}}
                                        </a>,
                                    @endforeach
                                    @foreach($book_participant->book_chapters as $book_chapter)
                                        <a class="text-secondary text-underline" href="{{route('web.dashboard.books.editions.chapters.edit', ['book' => $book_participant->book_id , 'edition' => $book_chapter->book_edition->edition_number , 'chapter' => $book_chapter->id ])}}">
                                            {{$book_chapter->name}}</a>,
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
{{--                        {{$contributors->links()}}--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
@section('scripts')
@include('web.partials.datatable')
@endsection
