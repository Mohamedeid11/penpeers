<nav>
    <ul class="nav justify-content-center nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{bookNavActive(['web.dashboard.books.authors.index'])}}"
               href="{{route('web.dashboard.books.authors.index', ['book'=>$book->id])}}">{{__('global.authors')}}</a>
        </li>
        @php
        $count_notification = count( auth()->user()->unreadNotifications()->where('data','LIKE','%"url_type":"join_request"%')->get() ) ;
        @endphp
        <li class="nav-item">
            <a class="nav-link {{bookNavActive(['web.dashboard.books.requests'])}}"
               href="{{ route('web.dashboard.books.requests', ['book'=>$book->id]) }}">{{ __('global.join_book_requests') }}
               @if($count_notification > 0)  <span class="badge badge-secondary">{{ $count_notification }}</span> @endif
            </a>
        </li>
    </ul>
</nav>
