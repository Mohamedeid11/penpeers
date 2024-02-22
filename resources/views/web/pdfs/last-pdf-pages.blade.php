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
                <!-- Back cover -->
                <img class="cover-back" src="{{storage_asset($book->back_cover)}}" style="width:99%">
            @endif
        </div>
    </div>
@endif
