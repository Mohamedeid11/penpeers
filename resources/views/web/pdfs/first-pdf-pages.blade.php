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

@if(auth()->check() && in_array(Auth::id(),  $book_participants))
    <div class="ck-content">
        <div id="editor" style="@if($language == 'ar') direction: rtl @endif">
            @if($book->completed == 1)
                <!-- Cover image page -->
                <img class="cover-front" src="{{storage_asset($book->front_cover)}}" style="width:100%">
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
                                <h1 style="text-align: center"><span class="text-huge">{{$book->title}}</span></h1>
                                <br /> <br />
                                <p style="text-align: center;"><em><span class="text-big">{{$book->description}}</span></em></p>
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
                                    <a href="{{route('web.author_books', ['author' => $lead_author[0]->user_id, 'type'=> 'lead_books'])}}">
                                        <span class="text-huge">{{$lead_author[0]->user->name}}</span>
                                    </a>
                                </p>
                                @if (count($co_authors) > 0)
                                    @foreach ($co_authors as $user)
                                        <p style="text-align: center">
                                            <a href="{{route('web.author_books', ['author' => $user->user_id, 'type'=> 'lead_books'])}}">
                                                <span class="text-huge">{{$user->user->name}}</span>
                                            </a>
                                        </p>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </figure>
                <div class="page-break" style="page-break-after:always;">
                    <span style="display:none;">&nbsp;</span>
                </div>

                <!-- Index page -->
                <p style="text-align: center">
                    <em>
                <span class="text-huge">
                    @if($language == "ar")
                        الفهرس
                    @else
                        Table of Contents
                    @endif
                </span>
                    </em>
                </p>
                <figure class="table" style="width: 100%">
                    <table style="border-style:hidden;">
                        <tbody>
                        @foreach($introChapters as $chapter)
                            <tr>
                                <td style="border: 1px solid transparent; padding: 0.7em; @if($language == 'ar') text-align: right; @endif">
                                <span class="text-big">
                                    <a href="#intro">
                                        @if($language == "ar")
                                            فصل المقدمة
                                        @else
                                            Intro chapter
                                        @endif
                                    </a>
                                    <a target="_blank" href="{{route('web.author_books', ['author' => $chapter->lead_author->id, 'type'=> 'lead_books'])}}">
                                        ({{$chapter->lead_author->name}})
                                    </a>
                                </span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td
                                style="border: 1px solid transparent; padding: 0.7em; @if($language == 'ar') text-align: right; @endif">
                            <span class="text-big">
                                @if($language == "ar")
                                    الفصول
                                @else
                                    Chapters
                                @endif
                            </span>
                            </td>
                        </tr>
                        @foreach($regularChapters as $chapter)
                            <tr>
                                <td
                                    style="border: 1px solid transparent; padding: 0.7em; @if($language == 'ar') text-align: right; @endif">
                                <span class="text-big"> &nbsp; &nbsp; &nbsp;
                                    <a href="#{{$chapter->id}}">
                                        {{$chapter->order}}- {{$chapter->name}}
                                    </a>
                                    <a target="_blank" href="{{route('web.author_books', ['author' => $chapter->authors[0]->id, 'type'=> 'lead_books'])}}">
                                        ({{$chapter->authors[0]->name}})
                                    </a>
                                </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </figure>
                <div class="page-break" style="page-break-after:always;">
                    <span style="display:none;">&nbsp;</span>
                </div>
            @endif
        </div>
    </div>
@endif
