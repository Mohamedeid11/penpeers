@extends('web.layouts.dashboard')
@section('heads')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/ckeditor/styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/web/ck-editor-custom.css')}}">
@endsection
@section('content')
<main class="main-page">
    @php
    $menu = [[
        'title' => 'Manage '.$book->trans('title'),
        'type' => 'active'
    ]];
    @endphp
    @include('web.partials.book-top-bar', ['menu'=>$menu, 'back_url' => route('web.dashboard.books.editions.chapters.index', ['book'=>$book->id, 'edition'=> 1])])
    <!-- Edit chapter details -->
    @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
        <section class="section">
            <div class="panel p-2">
                <header class="panel-heading d-flex flex-wrap justify-content-between">
                    <div class="panel-title">
                        <h2 class="h5">{{ __('global.edit_chapter_details') }}</h2>
                    </div>
                    <a href="{{ route('web.dashboard.books.editions.chapters.index', ['book' => $book->id, 'edition'=>1]) }}"
                        class="btn btn-secondary"><i class="fa-solid fa-backward"></i>
                        {{ __('global.back_to_cahpters') }}</a>
                </header>

                <div class="panel-body">
                    <div class="container-fluid">
                        <form class="form" method="POST" action="{{route('web.dashboard.books.editions.chapters.update',
                                    ['book'=>$book->id, 'edition'=>$edition->edition_number, 'chapter'=>$chapter->id])}}">
                            {{@csrf_field()}}
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-12 col-md-3">
                                    <label class="my-1 mr-2"
                                        for="title-input">{{ __('global.chapter_title') }}</label>
                                    <input type="text" class="form-control my-1 mr-sm-2 @if($errors->has('name')) is-invalid @endif" id="title-input" value="{{inp_value($chapter, 'name')}}" name="name">
                                    @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('name') as $error)
                                        {{$error}}<br>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label class="my-1 mr-2"
                                        for="order-input">{{ __('global.sequence') }}</label>
                                    <input id="order-input" type="number" step="1" class="form-control my-1 mr-sm-2 @if($errors->has('order')) is-invalid @endif" value="{{inp_value($chapter, 'order')}}" name="order">
                                    @if($errors->has('order'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('order') as $error)
                                        {{$error}}<br>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label class="my-1 mr-2"
                                        for="author_id-input">{{ __('global.assign_author') }}</label>
                                    <select class="form-control my-1 mr-sm-2 @if($errors->has('author_id')) is-invalid @endif" id="author_id-input" name="author_id" readonly disabled>
                                        <option  value=''>Choose...</option>
                                        @foreach($book->book_participants()->active()->get() as $book_participant)
                                        <option value="{{$book_participant->user->id}}" {{select_value($chapter, 'author_id', $book_participant->user->id)}}>
                                            {{$book_participant->user->name}}
                                        </option>
                                        @endforeach
                                    </select>
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
                                    <input id="deadline-input" type="date" class="form-control my-1 mr-sm-2 @if($errors->has('deadline')) is-invalid @endif" value="{{inp_value($chapter, 'deadline')}}" name="deadline" min="{{date('Y-m-d')}}">
                                    @if($errors->has('deadline'))
                                    <div class="invalid-feedback">
                                        @foreach($errors->get('deadline') as $error)
                                        {{$error}}<br>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>

                                <button type="submit" id="submit-btn" name="edit-chapter-form" class="btn btn-primary my-1 mx-auto">{{__('global.update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Edit content -->
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="panel p-2">
                    <header class="panel-heading mb-4 d-flex justify-content-between align-items-center flex-wrap">
                        <div class="panel-title w-100">
                            @if($chapter->authors[0]->id == auth()->id())
                                <h5 class="text-center mx-auto">{{__('global.write')}}...</h5>
                            @else
                                <h5 class="text-center mx-auto">{{ __('global.view') }}
                                    {{ $chapter->name }} {{ __('global.chapters.title') }}</h5>
                            @endif
                            @if($errors->has('remark'))
                                <div class="alert-danger">
                                    @foreach($errors->get('remark') as $error)
                                        {{$error}}<br>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <p class="mr-1">{{ __('global.help_editor') }}
                            <a href="{{ asset('editor-guide.pdf') }}" target="_blank" class="text-underline" style="color: #ce7852;">{{__('global.here')}}</a>
                        </p>
                        @if(!$book->deleted_at)
                            @if($chapter->completed && $book->lead_author->id == auth()->id())
                            <form class="form" method="POST" id="reedit-book-form"
                                action="{{ route('web.dashboard.books.editions.chapters.not_completed_email', ['book'=>$book, 'edition'=>$edition, 'chapter'=>$chapter]) }}">
                                @csrf
                                <button class="btn btn-danger" type="button" onclick="reeditChapterHandler()">
                                    {{ __('global.reedit_chapter') }}
                                </button>
                            </form>

                            @elseif($chapter->authors[0]->id == auth()->id() && ! $chapter->completed)
                                <form method="post" id="inform-lead-finsihed" action="{{route('web.dashboard.books.editions.chapters.completed_email', ['book'=>$book, 'edition'=>$edition, 'chapter'=>$chapter])}}">
                                    @csrf
                                    <a type="submit" class="btn btn-primary"><i class="fa-solid fa-check-circle"
                                            aria-hidden="true"></i>
                                        {{ __('global.complete_chapter') }}</a>
                                </form>
                            @endif
                        @endif
                    </header>

                    <div class="panel-body">
                        <div data-editor="DecoupledDocumentEditor" data-collaboration="false">
                            <div class="centered">
                                <div class="d-flex py-2 align-items-start editor-status-container">
                                    @if(($chapter->authors[0]->id == auth()->id() && ! $chapter->completed) || ($book->lead_author->id == auth()->id() && $chapter->completed))
                                        <div id="editor-status" class="px-2 font-weight-bolder"></div>
                                    @endif
                                </div>
                                <div class="row toolbar-container">
                                    <div class="document-editor__toolbar"></div>
                                </div>
                                <div class="row row-editor">
                                    <div class="editor-container">
                                        <div id="editor" class="editor">
                                            {!! $chapter->content !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="re-edit-modal" tabindex="-1" aria-labelledby="re-edit-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title h5" id="re-edit-label">{{ __('global.remarks_heading') }}
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" method="POST" action="{{route('web.dashboard.books.editions.chapters.not_completed_email', ['book'=>$book, 'edition'=>$edition, 'chapter'=>$chapter])}}">
                    @csrf
                    <div class="modal-body text-white">
                        <div class="form-group">
                            <label for="remark"
                                style="color: white !important;">{{ __('global.remarks') }} <sup
                                    class="color-danger">*</sup></label>
                            <textarea  class="form-control @if($errors->has('remark')) is-invalid @endif" id="remark" name="remark"  data-validation="required" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary">{{__('global.send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@section('scripts')
@include('web.partials.axiosinit')
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/super-build/ckeditor.js"></script>
<script>
    const chapter = {!! json_encode($chapter) !!};
    const chapterName = chapter.name;
    const isCompleted = chapter.completed;
    const pdfFileURL = "{{asset('css/web/pdf.css')}}";
    const pdf2FileURL = "{{asset('css/web/pdf2.css')}}";
    const language = chapter.book.language;
    const chapterAuthor = chapter.authors[0].id;
    const authorId = {!! json_encode(auth()->id()) !!};
    const isDisabled = chapterAuthor !== authorId || chapter.completed || {!! json_encode($book) !!}.deleted_at;
    const api_url =
    "{{ route('web.api.dashboard.books.editions.chapters.update', ['book'=>$book->id, 'edition'=>$edition->edition_number, 'chapter'=>$chapter->id]) }}";
    const writingText = "{{ __('global.writing') }}";
    const saveText = "{{ __('global.save') }}";
    const savedText = "{{ __('global.saved') }} {{ __('global.complete_chapter_btn') }}";
    const reeditChapterText = "{{ __('global.reedit_chapter_btn') }}";
    const userId = {!! json_encode(auth()->id()) !!};

    $('body').on('click', '#inform-co-not-finsihed', function(e) {
        e.preventDefault();
        swal({
            title: "{{__('global.remarks_heading')}}",
            type: 'error',
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{__('global.ok')}}",
        }, function() {
            $('#inform-co-not-finsihed').submit();

        });
    });

    $('body').on('click', '#inform-lead-finsihed', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.complete_chapter_modal') }}",
            type: 'success',
            confirmButtonClass: "btn-primary",
            showCancelButton: true,
            cancelButtonText: "{{ __('global.cancel') }}",
            confirmButtonText: "{{ __('global.ok') }}",
        }, async function() {
            await saveData(window.editor.getData());
            $('#inform-lead-finsihed').submit();

        });
    });

    const reeditChapterHandler = () => {
        if(chapter.authors[0].id === userId)
            document.querySelector("#reedit-book-form").submit();
        else $("#re-edit-modal").modal("show");
    }
</script>
<script src="{{asset('js/web/editBook.js')}}"></script>
@endsection
