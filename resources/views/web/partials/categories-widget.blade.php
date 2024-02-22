<aside class="wedget__categories poroduct--cat col-lg-3 col-12 md-mt-40 sm-mt-40 order-2 order-lg-1">
    <h3 class="wedget__title">{{ __('global.book_genres') }}</h3>
    <ul>
        @if($type == 'lookingForCoAuthor')
            <li><a href="{{ route('web.books_need_authors') }}" @if(!$book_genre) class="active"
                    @endif>{{__('global.all_books_looking_coauthors')}}</a></li>
        @else
            <li><a href="{{ route('web.books') }}" @if(!$book_genre) class="active"
                    @endif>{{ __('global.all_books') }}</a></li>
        @endif
        @foreach($genres as $genre)
            @if($type == 'lookingForCoAuthor')
                <li>
                    <a href="{{ route('web.books_need_authors', ['genre'=>$genre->id]) }}"
                        @if($book_genre == $genre->id) class="active" @endif
                        >{{ $genre->trans('name') }}
                        <span>
                            {{ $genre->genre_num_of_books_need_authors }}
                        </span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('web.books', ['genre'=>$genre->id, 'filter_type' => request()->filter_type]) }}"
                        @if($book_genre == $genre->id) class="active" @endif
                        >{{ $genre->trans('name') }}
                        <span>
                            {{ $genre->genre_num_of_books }}
                        </span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</aside>
