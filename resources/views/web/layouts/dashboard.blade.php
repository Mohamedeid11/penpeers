<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/web/logo/favicon.png') }}">
    <title>PENPEERS Dashboard</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/web/'.ldir().'/bootstrap.min.css') }}">
    <link href="{{ asset('css/admin/bootstrap4-toggle.min.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('css/web/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/web/dashboard.css') }}">

    <!-- Cusom css -->
    <link rel="stylesheet" href="{{ asset('css/web/'.ldir().'/dashboard.css') }}" type="text/css" media="all" />
    <link href="{{ asset('plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet"
          type="text/css">
    <link rel="stylesheet" href="{{ asset('css/web/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/web/'.ldir().'/style.css') }}" type="text/css" media="all" />
    @yield('heads')
</head>

<body class="top-navbar-fixed">
<div class="main-wrapper">

    <!-- ========== TOP NAVBAR ========== -->
    @include('web.partials.header')

    <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
    <div class="content-wrapper">
        <div class="content-container d-lg-table-row d-flex">

            <!-- ========== LEFT SIDEBAR ========== -->
            <button class="btn btn-primary position-fixed rounded-0 d-block d-lg-none collapse-nav"
                    style="z-index:200" type="button" data-toggle="collapse" data-target="#collapseWidthExample"
                    aria-expanded="false" aria-controls="collapseWidthExample">
                <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
            </button>

            <aside class="collapse width left-sidebar fixed-sidebar bg-black-300 box-shadow tour-three d-lg-table-cell"
                   id="collapseWidthExample">
                <nav class="sidebar-content pt-2">
                    @php
                        $book_routes = ['web.dashboard.books.index', 'web.dashboard.books.*'];
                        $dashboard_routes = ['web.dashboard.index'];
                        $invitation_routes = ['web.dashboard.author_emailInvitations',
                        'web.dashboard.author_receivedInvitations'];
                        $blog_routes = ['web.dashboard.blogs.index', 'web.dashboard.blogs.*'];
                        $buy_request_notification = count( auth()->user()->unreadNotifications()->where('data','LIKE','%"url_type":"buy_request"%')->get() ) ;

                        $invitation_notification = count( auth()->user()->unreadNotifications()->where(function($query){$query->orWhere('data', 'LIKE', '%"url_type":"received_invitations"%')
                                                                                                                                ->orWhere('data', 'LIKE', '%"url_type":"accept_registered_author"%')
                                                                                                                                ->orWhere('data', 'LIKE', '%"url_type":"reject_registered_author"%')
                                                                                                                                ->orWhere('data', 'LIKE', '%"url_type":"accepted_unregistered_author"%')
                                                                                                                                ->orWhere('data', 'LIKE', '%"url_type":"reject_unregistered_author"%');})->get() ) ;
                    @endphp
                    <ul class="side-nav">
                        <li class="nav-header">
                            {{ __('global.quick_details') }}
                        </li>
                        <li class="{{ bookNavActive($dashboard_routes) }}" data-title="Dashboard">
                            <a href="{{ route('web.dashboard.index') }}">
                                <i class="fa-solid fa-dashboard"></i> {{__('global.dashboard')}}
                            </a>
                        </li>
                        <li class="@if(dashboard_menu_active(['web.dashboard.books.create'])) active @endif"
                            data-title="Create Book">
                            <a href="{{ route('web.dashboard.books.create') }}">
                                <i class="fa-solid fa-square-plus"></i> {{ __('global.create_book') }}
                            </a>
                        </li>
                        <li class="@if(dashboard_menu_active($book_routes)) active @endif"
                            data-title="My Books">
                            <a href="{{ route('web.dashboard.books.index') }}">
                                <i class="fa-solid fa-book"></i> {{ __('global.my_books') }}
                            </a>
                        </li>
                        <li class="@if(dashboard_menu_active(['web.dashboard.buying_requests'])) active @endif"
                            data-title="Buying Requests">
                            <a href="{{ route('web.dashboard.buying_requests') }}">
                                <i class="fa-solid fa-money-bill"></i> {{ __('global.book_buying_requests') }}

                                @if($buy_request_notification > 0) <span class="badge badge-secondary">{{ $buy_request_notification}}</span> @endif
                            </a>
                        </li>
                        <li class="@if(dashboard_menu_active($invitation_routes)) active @endif"
                            data-title="My Invitations">
                            <a href="{{ route('web.dashboard.author_receivedInvitations') }}">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> {{ __('global.my_invitations') }}
                                @if($invitation_notification > 0) <span class="badge badge-secondary">{{  $invitation_notification }}</span> @endif
                            </a>
                        </li>
                        <li class="@if(dashboard_menu_active($blog_routes)) active @endif" data-title="My Blog">
                            <a href="{{ route('web.dashboard.blogs.index') }}">
                                <i class="fa-solid fa-pencil"></i> {{ __('global.my_blog') }}
                            </a>
                        </li>
                        <li class="nav-header">
                            {{ __('global.personals') }}
                        </li>
                        <li data-title="My Account">
                            <a href="{{ route('web.dashboard.account.edit.show') }}">
                                <i class="fa-solid fa-gear"></i> {{__('global.my_account')}}
                            </a>
                        </li>
                        @php
                            $class = ! auth()->user()->validity ? 'text-danger': (auth()->user()->can_renew? 'text-warning' : '');
                        @endphp
                        <li data-title="Account Status">
                            <a href="{{ route('web.dashboard.account.status') }}" class="{{$class}}">
                                <i class="fa-solid fa-gear {{$class}}"></i> {{ __('global.account_status') }}
                            </a>
                        </li>
                    </ul>
                    <!-- /.sidebar-nav -->
                </nav>
                <!-- /.sidebar-content -->
            </aside>
            <!-- /.left-sidebar -->

            @yield('content')

            <footer class="footer w-100 bg-black-300 text-center py-3 position-absolute footer-bottom">
                &copy; {{ date('Y') }} PenPeers - {{__('global.allrights_reserved')}}.
            </footer>
            <!-- /.main-page -->
        </div>
        <!-- /.content-container -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- /.main-wrapper -->

<script src="{{ asset('js/web/vendor/modernizr-3.5.0.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-sweetalert/sweet-alert.min.js') }}"></script>
<script src="{{ asset('js/web/plugins.js') }}"></script>
<script>
    const lang = "{{ locale() }}";
    const navText = ['<i class="zmdi zmdi-chevron-left"></i>', '<i class="zmdi zmdi-chevron-right"></i>'];
    if (lang === "ar") navText.reverse();

    // Toggle tooltips
    $('[data-toggle="tooltip"]').tooltip();
</script>
<script src="{{ asset('js/web/active.js') }}"></script>

@yield('scripts')
@yield('pdf-scripts')
</body>

</html>
