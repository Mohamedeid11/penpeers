@php
$overview_routes = ['web.dashboard.books.editions.edition_settings', 'web.dashboard.books.edit'];
$editor_routes = ['web.dashboard.books.editions.special_chapters.index', 'web.dashboard.books.editions.chapters.index', 'web.dashboard.books.editions.edit', 'web.dashboard.books.editions.index' ,'web.dashboard.books.editions.create', 'web.dashboard.books.editions.edition_settings'];
$authors_routes = ['web.dashboard.books.authors.index','web.dashboard.books.requests'];
@endphp
<section class="section pb-0">
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-12">
                <ul class="nav justify-content-center nav-tabs m-auto" id="edition-content">
                    <li class="nav-item">
                        <a class="nav-link {{ bookNavActive(['web.dashboard.books.authors.index','web.dashboard.books.requests']) }}"
                            href="{{ route('web.dashboard.books.authors.index', ['book'=>$book->id]) }}">{{ __('global.authors') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ bookNavActive(['web.dashboard.books.editions.special_chapters.index']) }}"
                            href="{{ route('web.dashboard.books.editions.special_chapters.index', ['book'=>$book->id, 'edition'=> 1]) }}">{{ __('global.introduction') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{bookNavActive(['web.dashboard.books.editions.chapters.index'])}}" href="{{route('web.dashboard.books.editions.chapters.index', ['book'=>$book->id, 'edition'=> 1])}}">{{__('global.chapters_nav')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ bookNavActive(['web.dashboard.books.editions.edition_settings']) }}"
                            href="{{ route('web.dashboard.books.editions.edition_settings', ['book'=>$book->id, 'edition'=> 1]) }}">{{ __('global.actions') }}</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>
