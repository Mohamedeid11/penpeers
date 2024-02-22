@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/ckeditor/styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/web/ck-editor-custom.css') }}">
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
            <header>
                @include('web.partials.book-nav-section')
            </header>

            <section class="panel p-2">
                <header class="panel-heading mb-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div class="panel-title w-100">
                        @if($book->lead_author->id == auth()->id())
                            <h2 class="text-center h5">{{ __('global.edit_introduction') }}</h2>
                        @else
                            <h2 class="text-center h5">{{ __('global.view_introduction') }}</h2>
                        @endif
                    </div>
                    <p class="mr-1">
                        {{ __('global.help_editor') }}
                        <a href="{{ asset('editor-guide.pdf') }}" target="_blank"
                            class="text-underline" style="color: #ce7852;">{{ __('global.here') }}</a>
                    </p>
                    @if(!$book->deleted_at && $book->lead_author->id == auth()->id())
                        @if($book->book_special_chapters[0]->completed)
                            <form method="post" id="inform-co-not-finsihed" class="inform-co-not-finsihed" action="{{route('web.dashboard.books.editions.special_chapters.not_completed_email', ['book'=>$book, 'edition'=>$edition, 'special_chapter'=>$book->book_special_chapters[0]->id])}}">
                                @csrf
                                <a type="submit" class="inform-co-not-finsihed btn btn-danger"><i class="fa-solid fa-xmark"
                                        aria-hidden="true"></i> {{ __('global.reedit_introduction') }}</a>
                            </form>
                        @elseif(!$book->book_special_chapters[0]->completed)
                            <form method="post" id="inform-lead-finsihed" class="inform-lead-finsihed" action="{{route('web.dashboard.books.editions.special_chapters.completed_email', ['book'=>$book, 'edition'=>$edition, 'special_chapter'=>$book->book_special_chapters[0]->id])}}">
                                @csrf
                                <a type="submit" class="inform-lead-finsihed btn btn-primary"><i class="fa-solid fa-circle-check"
                                        aria-hidden="true"></i>
                                    {{ __('global.complete_introduction') }}</a>
                            </form>
                        @endif
                    @endif
                </header>

                <div class="panel-body mt-4">
                    <div data-editor="DecoupledDocumentEditor" data-collaboration="false">
                        <div class="centered">
                            <div class="d-flex py-2 align-items-start editor-status-container">
                                @if($book->lead_author->id == auth()->id())
                                    <div id="editor-status" class="font-weight-bolder"></div>
                                @endif
                            </div>
                            <div class="row toolbar-container">
                                <div class="document-editor__toolbar"></div>
                            </div>
                            <div class="row row-editor">
                                <div class="editor-container">
                                    <div id="editor" class="editor">
                                        {!! $book->book_special_chapters[0]->content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</main>
@endsection
@section('scripts')
@include('web.partials.datatable')
@include('web.partials.axiosinit')
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/super-build/ckeditor.js"></script>
<script>
    const chapter = {!! json_encode($book->book_special_chapters[0]) !!};
    const chapterName = chapter.special_chapter.display_name;
    const isCompleted = chapter.completed;
    const pdfFileURL = "{{asset('css/web/pdf.css')}}";
    const pdf2FileURL = "{{asset('css/web/pdf2.css')}}";
    const language = chapter.book.language;
    const authorsURL = "{{route('web.dashboard.books.authors.index', ['book'=>$book->id])}}"
    const bookLeadAuthorId = {!! json_encode($book->lead_author->id) !!};
    const leadAuthorId = {!! json_encode(auth()->id()) !!};
    const isDisabled = bookLeadAuthorId != leadAuthorId || {!! json_encode($book) !!}.deleted_at;
    const api_url ="{{route('web.api.dashboard.books.editions.special_chapters.update', ['book'=>$book->id, 'edition'=>$edition->edition_number, 'special_chapter'=>$book->book_special_chapters[0]->id])}}";
    const writingText = "{{ __('global.writing') }}";
    const saveText = "{{ __('global.save') }}";
    const savedText = `{{ __('global.saved_introduction') }} <a href='${authorsURL}'
        class='text-success'>{{ __('global.invite_authors') }}</a>`;
    const reeditChapterText = "{{ __('global.reedit_intro_btn') }}"

    $('body').on('click', '#inform-co-not-finsihed', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.reedit_introduction_modal') }}",
            type: 'error',
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{ __('global.ok') }}"
        }, function() {
            $('#inform-co-not-finsihed').submit();

        });
    });

    $('body').on('click', '#inform-lead-finsihed', function(e) {
        e.preventDefault();
        swal({
            title: "{{__('global.complete_introduction')}}?",
            type: 'success',
            confirmButtonClass: "btn-primary",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{ __('global.ok') }}"
        }, async function() {
            await saveData(window.editor.getData());
            $('#inform-lead-finsihed').submit();
        });
    });
</script>
<script src="{{asset('js/web/editBook.js')}}"></script>
@endsection
