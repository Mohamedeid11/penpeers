@php
    $introChapters = $book->book_special_chapters()->with('authors')->whereHas('special_chapter', function($q){
            $q->intro();
        })->get();
    $regularChapters = $book->book_chapters()->with('authors')->get();
    $lead_author =$book->book_participants()->leadAuthor($book->id)->get();
    $co_authors = $book->book_participants()->notALeadAuthor()->get();
    $book_participants = $book->participants()->pluck('user_id')->toArray();
    $language = $book->language;
@endphp

@if(auth()->check() && in_array(Auth::id(),  $book_participants))
<div class="ck-content">
    <div id="editor" style="@if($language == 'ar') direction: rtl @endif">

        <!-- Chapters -->
        @if (count($regularChapters) > 0)
            <!-- Chapter title -->
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
                                    {{$chapter->order}}
                                </span>
                            </p>
                            <h1 style="text-align:center" id="{{$chapter->id}}"><span class="text-huge">{{$chapter->name}}</span></h1>
                            <br/> <br/>
                            <p style="text-align:center">
                                <span class="text-big">
                                    @if($language == "ar")
                                        كُتبت بواسطة
                                    @else
                                        Written by:
                                    @endif
                                </span>
                            </p>
                            @foreach ($chapter->authors as $author)
                                <p style="text-align:center">
                                    <a target="_blank" href="{{route('web.author_books', ['author' => $author->id, 'type'=> 'lead_books'])}}">
                                        <span class="text-huge">{{$author->name}}</span>
                                    </a>
                                </p>
                            @endforeach
                        </td>
                    </tr>
                    </tbody>
                </table>
            </figure>
            <div class="page-break" style="page-break-after:always;">
                <span style="display:none;">&nbsp;</span>
            </div>

            <!-- Chapter content -->
            <p>
                @if($language == "ar")
                    فصل
                @else
                    Chapter
                @endif
                {{ $chapter->order}}
            </p>
            <hr style="height: 1px; margin-top: 0"/>
            <p><span class="text-huge">{{$chapter->name}}</span></p>
            <br /> <br />
            {!! $chapter->content !!}
            <div class="page-break" style="page-break-after:always;">
                <span style="display:none;">&nbsp;</span>
            </div>
        @endif

    </div>
</div>
@endif
