@extends('web.layouts.dashboard')
@section('heads')
<link rel="stylesheet" href="{{asset('css/web/dashboard-main.css')}}">
@endsection
@section('content')
<main class="dasboard-main">
    <header>
        <a href="{{ url()->previous() }}">
            @if(locale() == "ar")
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            @else
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            @endif
            {{ __('global.back') }}</a>
        <p class="dashboard-title">{{__('global.welcome')}}: {{auth()->user()->name}}</p>
        <h2 class="dashboard-sub-title">{!! __('global.my_dashboard_heading') !!}</h2>
    </header>

    <section class="mt-3 p-4 d-flex flex-wrap justify-content-center">
        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.create')}}">
                <span class="icon">
                    <img src="{{asset('images/web/create-new-book.svg')}}" alt="Re-editing icon">
                </span>
                <span class="name">{{__('global.create_book')}}</span>
            </a>
        </div>
        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.index', ['type' => 'draft'])}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-ongoing.svg')}}" alt="Ongoing icon">
                </span>
                <span class="name">{{__('global.ongoing')}}</span>
                <span class="number counter">{{$draft}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.index', ['type' => 'redit'])}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-reediting.svg')}}" alt="Re-editing icon">
                </span>
                <span class="name">{{__("global.reediting")}}</span>
                <span class="number counter">{{$reditingBooks}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.index', ['type' => 'completed'])}}">
                <span class="icon">
                    <img src="{{asset('images/web/completed-books.svg')}}" alt="Completed icon">
                </span>
                <span class="name">{{__('global.completed')}}</span>
                <span class="number counter">{{$completed}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.index', ['type' => 'shown'])}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-shown.svg')}}" alt="Shown on PenPeers icon">
                </span>
                <span class="name">{{ __('global.shown_on_penpeers') }}</span>
                <span class="number counter">{{$shownBooks}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.index', ['type' => 'hidden'])}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-hidden.svg')}}" alt="Hidden icon">
                </span>
                <span class="name">{{ __('global.hidden') }}</span>
                <span class="number counter">{{$hiddenBooks}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.author_receivedInvitations')}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-received.svg')}}" alt="Received invitations icon">
                </span>
                <span class="name">{{ __('global.received_invitations') }}</span>
                <span class="number counter">{{$receivedInvitations}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.author_participants')}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-send.svg')}}" alt="Sent invitations icon">
                </span>
                <span class="name">{{ __('global.sent_invitations') }}</span>
                <span class="number counter">{{$registeredAuthorsInvitations}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.author_emailInvitations')}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-email.svg')}}" alt="Direct email icon">
                </span>
                <span class="name">{{ __('global.direct_email_invitations') }}</span>
                <span class="number counter">{{$emailInvitations}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.index', ['type' => 'Participations'])}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-author.svg')}}" alt="Me as co-author icon">
                </span>
                <span class="name">{{ __('global.me_as_coauthor') }}</span>
                <span class="number counter">{{$contribution}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.books.contributors')}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-coauthor.svg')}}" alt="Co-authors participations icon">
                </span>
                <span class="name">{{ __('global.coauthor_participation') }}</span>
                <span class="number counter">{{$contributors}}</span>
            </a>
        </div>

        <div class="dashboard-stat">
            <a class="mx-2" href="{{route('web.dashboard.blogs.index')}}">
                <span class="icon">
                    <img src="{{asset('images/web/dashboard-posts.svg')}}" alt="Posts icon">
                </span>
                <span class="name">{{ __('global.my_blog_posts') }}</span>
                <span class="number counter">{{$blogs}}</span>
            </a>
        </div>
    </section>
</main>

@endsection
