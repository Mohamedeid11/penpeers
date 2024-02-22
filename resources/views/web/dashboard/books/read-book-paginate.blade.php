@extends("web.layouts.dashboard")
@php
    $introChapters = $book->book_special_chapters()->with('authors')->whereHas('special_chapter', function($q){
        $q->intro();
    })->get();
    $regularChapters = $book->book_chapters()->with('authors')->get();
    $lead_author =$book->book_participants()->leadAuthor($book->id)->get();
    $co_authors = $book->book_participants()->notALeadAuthor()->get();
    $book_participants = $book->participants()->pluck('user_id')->toArray();
    $language = $book->language
@endphp
@section('heads')
<link rel="stylesheet" type="text/css" href="{{ asset('css/web/ckeditor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/web/ck-editor-custom.css') }}">
@endsection
@section('content')
<main class="main-page">
    @include('web.partials.dashboard-header', ['title' => $book->trans('title'), 'sub_title' => __('global.my_books_sub_heading'), 'current' => '<li class="active">'.__('global.my_books').'</li>'])
    <section class="section">
        <div class="container-fluid p-0 overflow-auto ck-editor-read bg-white px-3">
            @if(auth()->check() && in_array(Auth::id(),  $book_participants))
                <div id="editor" style="@if($language == 'ar') direction: rtl @endif">
                    @if($book->completed == 1)
                        <!-- Cover image page -->
                        <img class="cover-front" src="{{ storage_asset($book->front_cover) }}" style="width:100%">
                        <div class="page-break" style="page-break-after:always;"><span style="display:none;">&nbsp;</span></div>

                        <!-- Title page -->
                        <figure class="table">
                            <table style="border-style:hidden;">
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: center; height: 227mm">
                                            <p style="text-align: center;">
                                                <span class="text-huge">
                                                    @if($language == "ar") هذا هو @else This is @endif
                                                </span>
                                            </p>
                                            <h1 style="text-align: center"><span class="text-huge">{{ $book->title }}</span></h1>
                                            <br /> <br />
                                            <p style="text-align: center;"><em><span
                                                        class="text-big">{{ $book->description }}</span></em></p>
                                            <br /> <br />
                                            <p style="text-align: center">
                                                <span class="text-big">
                                                    @if($language == "ar")
                                                        كُتبت بواسطة
                                                    @else
                                                        Written by:
                                                    @endif
                                                </span>
                                            </p>
                                            <p style="text-align: center">
                                                <a
                                                    href="{{ route('web.author_books', ['author' => $lead_author[0]->user_id, 'type'=> 'lead_books']) }}">
                                                    <span class="text-huge">{{ $lead_author[0]->user->name }}</span>
                                                </a>
                                            </p>
                                            @if(count($co_authors) > 0)
                                                @foreach($co_authors as $user)
                                                    <p style="text-align: center">
                                                        <a
                                                            href="{{ route('web.author_books', ['author' => $user->user_id, 'type'=> 'lead_books']) }}">
                                                            <span class="text-huge">{{ $user->user->name }}</span>
                                                        </a>
                                                    </p>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </figure>
                        <hr />
                    @endif

                    <!-- Chapters -->
                    @if(count($introChapters) > 0)
                        <!-- Chapter title -->
                        <figure class="table">
                            <table style="border-style:hidden;">
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: center; height: 227mm">
                                            <h1 style="text-align:center" id="intro">
                                                <span class="text-huge">
                                                    @if($language == "ar")
                                                        مقدمة الكتاب
                                                    @else
                                                        Book Intro
                                                    @endif
                                                </span>
                                            </h1>
                                            <br /> <br />
                                            <p style="text-align:center">
                                                <span class="text-big">
                                                    @if($language == "ar")
                                                        كُتبت بواسطة
                                                    @else
                                                        Written by:
                                                    @endif
                                                </span>
                                            </p>
                                            @foreach($introChapters[0]->authors as $author)
                                                <p style="text-align:center">
                                                    <a target="_blank"
                                                        href="{{ route('web.author_books', ['author' => $author->id, 'type'=> 'lead_books']) }}">
                                                        <span class="text-huge">{{ $author->name }}</span>
                                                    </a>
                                                </p>
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </figure>
                        <hr />

                        <!-- Chapter content -->
                        @foreach($introChapters as $chapter)
                            {!! $chapter->content !!}
                        @endforeach
                        <hr />
                    @endif
                </div>
            @endif
        </div>
    </section>
</main>
@include('web.partials.axiosinit')
<script>
    let flag = false;
    let nextPageUrl =
    "{{ route('api.preview.book', ['book'=>$book, 'edition'=>'1']) }}";

    const getAuthor = (author) => {
        let data = `<p style="text-align:center">
            <a target="_blank"
                href="{{ route('web.author_books', ['author' => 'author_id', 'type'=> 'lead_books']) }}">
                <span class="text-huge">${author.name}</span>
            </a>
        </p>`;

        data = data.replace('author_id', author.id)

        return data;
    }

    document.querySelector(".ck-editor-read").addEventListener("scroll", (e) => {
        const target = e.target;
        if(target.scrollHeight - (target.clientHeight + target.scrollTop) < 300 && !flag && nextPageUrl) {
            flag = true;

            axios.get(nextPageUrl).then((response) => {
                if (response && response.status === 200) {
                    const chapter = response.data.book_chapter.data;
                    nextPageUrl = response.data.links.nextPageUrl;

                    const htmlContent = `<!-- Chapter title -->
                    <figure class="table">
                        <table style="border-style:hidden;">
                            <tbody>
                                <tr>
                                    <td style="vertical-align: center; height: 235mm">
                                        <p style="text-align: center">
                                            <span class="text-huge">
                                                @if($language == "ar")
                                                    فصل
                                                @else
                                                    Chapter
                                                @endif
                                                ${chapter[0].order}
                                            </span>
                                        </p>
                                        <h1 style="text-align:center" id="${chapter[0].id}"><span
                                                class="text-huge">${chapter[0].name}</span></h1>
                                        <br /> <br />
                                        <p style="text-align:center">
                                            <span class="text-big">
                                                @if($language == "ar")
                                                    كُتبت بواسطة
                                                @else
                                                    Written by:
                                                @endif
                                            </span>
                                        </p>
                                        ${chapter[0].authors?.map(author => getAuthor(author))}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </figure>
                    <hr />
                    <!-- Chapter content -->
                    <p>
                        @if($language == "ar")
                            فصل
                        @else
                            Chapter
                        @endif
                        ${response.data.book_chapter.current_page}
                    </p>
                    <hr style="height: 1px; margin-top: 0" />
                    <p><span class="text-huge">${chapter[0].name}</span></p>
                    <br /> <br />
                    ${chapter[0].content}
                    <hr />
                    ${!nextPageUrl ? `
                        <div class="page-break" style="page-break-after:always;">
                            <span style="display:none;">&nbsp;</span>
                        </div>
                        @if($book->completed == 1)
                            <!-- Back cover -->
                            <img class="cover-back" src="{{ storage_asset($book->back_cover) }}" style="width:99%">
                        @endif
                        ` : ``}
                    `;

                    document.querySelector("#editor").innerHTML += htmlContent;
                }
            });
        } else if(target.scrollHeight - (target.clientHeight + target.scrollTop) >= 300 && flag) {
            flag = false;
        }
    })
</script>
@endsection