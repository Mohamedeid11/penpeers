@php
    if ($book->lead_author->id != auth()->id()  && !in_array( auth()->id() ,$book->book_participants->pluck('user_id')->toArray() ) && $book->receivable() && ($book->completed == 2 || $book->completed == 0) ) {
    $type = "lookingForCoAuthor";
    } else {
    $type = "completed";
    }
@endphp

<section class="product product__style--3 text-center mb-4">
    <div class="product__thumb">
        <a class="first__img"
            href="{{ route('web.view_book', ['slug' => $book->slug , 'type'=> $type]) }}"><img
                src="{{ storage_asset($book->front_cover) }}" alt="front cover"></a>
        <a class="second__img animation1"
            href="{{ route('web.view_book', ['slug' => $book->slug, 'type'=> $type]) }}">
            <img src=" {{ storage_asset($book->back_cover) }}" alt="back cover">
        </a>
        @if($book->completed == 1)
            <div class="hot__box">
                <span class="hot-label">
                    @if ($book->sample)
                        {{__('global.samples')}}
                    @else
                        {{__('global.for_sale')}}
                    @endif
                </span>
            </div>
        @endif
    </div>

    @if($type == "lookingForCoAuthor")
        <a class="btn btn-secondary mb-2 mt-3"
            href="{{ route('web.get_book_request',['slug'=>$book->slug ]) }}"
            role="button">{{ __('global.participate') }}</a>
    @endif
</section>
