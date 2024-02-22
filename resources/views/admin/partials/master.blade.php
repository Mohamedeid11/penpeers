<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Dashboard" name="description" />
    <meta content="Ibrahim E.Gad" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title')</title>
    <!-- Favicons -->
    <link rel="shortcut icon" href="{{asset('images/web/logo/favicon.png')}}">
    <link rel="apple-touch-icon" href="{{asset('images/web/logo/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}">
    <link href="{{asset('css/admin/bootstrap'.(LaravelLocalization::getCurrentLocaleDirection() == 'rtl'?'.rtl':'').'.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/style'.(LaravelLocalization::getCurrentLocaleDirection() == 'rtl'?'.rtl':'').'.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/bootstrap4-toggle.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/custom.css')}}" rel="stylesheet" type="text/css">
    @yield('head')
</head>

<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">
        <!-- Top Bar Start -->
        <div class="topbar">
            <!-- LOGO -->
            <div class="topbar-left">
                <div class="text-center p-2 ">
                    <a href="{{route('admin.get_dashboard')}}" class="logo w-100"><img class="w-100" src="{{asset('images/web/logo/logo-dark.png')}}" alt="logo-img"></a>
                    <a href="{{route('admin.get_dashboard')}}" class="logo-sm"><img src="{{asset('images/web/logo/logo11.png')}}" alt="logo-img"></a>
                </div>
            </div>
            <!-- Button mobile view to collapse sidebar menu -->
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>
                        {{-- <li class="hide-phone app-search float-left">--}}
                        {{-- <form role="search" class="navbar-form">--}}
                        {{-- <input type="text" placeholder="Search..." class="form-control search-bar">--}}
                        {{-- <a href="#" class="btn-search"><i class="fa fa-search"></i></a>--}}
                        {{-- </form>--}}
                        {{-- </li>--}}
                    </ul>

                    <ul class="nav navbar-right float-right list-inline">
                        <li class="dropdown d-none d-sm-block">
                            <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light notification-icon-box" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-bell"></i> <span class="badge badge-xs badge-danger"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg">
                                <li class="text-center notifi-title">Notification <span class="badge badge-xs badge-success">3</span></li>
                                <li class="list-group">
                                    <!-- list item-->
                                    <a href="javascript:void(0);" class="list-group-item">
                                        <div class="media">
                                            <div class="media-heading">Your order is placed</div>
                                            <p class="m-0">
                                                <small>Dummy text of the printing and typesetting industry.</small>
                                            </p>
                                        </div>
                                    </a>
                                    <!-- list item-->
                                    <a href="javascript:void(0);" class="list-group-item">
                                        <div class="media">
                                            <div class="media-body clearfix">
                                                <div class="media-heading">New Message received</div>
                                                <p class="m-0">
                                                    <small>You have 87 unread messages</small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- list item-->
                                    <a href="javascript:void(0);" class="list-group-item">
                                        <div class="media">
                                            <div class="media-body clearfix">
                                                <div class="media-heading">Your item is shipped.</div>
                                                <p class="m-0">
                                                    <small>It is a long established fact that a reader will</small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- last list item -->
                                    <a href="javascript:void(0);" class="list-group-item">
                                        <small class="text-primary">See all notifications</small>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="d-none d-sm-block">
                            <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="mdi mdi-fullscreen"></i></a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                                <img src="{{asset('images/defaults/user.png')}}" alt="user-img" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="javascript:void(0)" class="dropdown-item"> Profile</a></li>
                                <li><a href="javascript:void(0)" class="dropdown-item"><span class="badge badge-success float-right">5</span> Settings </a></li>
                                <li><a href="javascript:void(0)" class="dropdown-item"> Lock screen</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a href="javascript:void(0)" class="dropdown-item"> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- Top Bar End -->
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="sidebar-inner slimscrollleft">

                <div class="user-details">
                    <div class="text-center">
                        <img src="{{asset('images/defaults/user.png')}}" alt="" class="thumb-md img-circle rounded-circle">
                    </div>
                    <div class="user-info m-0 text-center">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ Auth::guard('admin')->user()->name  }} <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile<div class="ripple-wrapper"></div></a></li>
                                <li><a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a></li>
                                <li><a href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a></li>
                                <li><a href="javascript:void(0)"><i class="md md-settings-power"></i> Logout</a></li>
                            </ul>
                        </div>
                        <p class="text-muted m-0">Admin</p>
                    </div>
                </div>
                <div id="sidebar-menu">
                    <ul>
                        <li class="menu-title">Menu</li>
                        <li>
                            <a href="{{route('admin.get_dashboard')}}" class="waves-effect"><i class="mdi mdi-home"></i>
                                <span>{{__('admin.dashboard')}}</span></a>
                        </li>
                        @php
                        $main_routes = ['admin.countries.index', 'admin.countries.*', 'admin.roles.index', 'admin.roles.*',
                        'admin.admins.index', 'admin.admins.*' ];
                        @endphp
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect @if(menu_active($main_routes)) active @endif"><i class="mdi mdi-album"></i> <span>{{__('admin.main')}}</span> <span class="float-right"><i class="mdi mdi-plus"></i></span></a>
                            <ul class="list-unstyled">
                                @can('viewAny', \App\Models\Role::class)
                                <li class="@if(menu_active(['admin.roles.index', 'admin.roles.*'])) active @endif">
                                    <a href="{{route('admin.roles.index')}}">
                                        <i class="mdi mdi-account-group"></i>
                                        {{__('global.roles')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Plan::class)
                                <li class="@if(menu_active(['admin.plans.index', 'admin.plans.*'])) active @endif">
                                    <a href="{{route('admin.plans.index')}}">
                                        <i class="mdi mdi-account-group"></i>
                                        {{__('global.plans')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Admin::class)
                                <li class="@if(menu_active(['admin.admins.index', 'admin.admins.*'])) active @endif">
                                    <a href="{{route('admin.admins.index')}}">
                                        <i class="mdi mdi-account-network"></i>
                                        {{__('global.admins')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Country::class)
                                <li class="@if(menu_active(['admin.countries.index', 'admin.countries.*'])) active @endif">
                                    <a href="{{route('admin.countries.index')}}">
                                        <i class="mdi mdi-globe-model"></i>
                                        {{__('global.countries')}}
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @php
                        $end_user_routes = ['admin.users.index', 'admin.users.*', 'admin.publish_requests.index', 'admin.publish_requests.*', 'admin.books.index', 'admin.books.*', 'admin.occupations.index', 'admin.occupations.*', 'admin.consults.index', 'admin.consults.*', 'admin.blogs.index', 'admin.blogs.*'];
                        @endphp
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect @if(menu_active($end_user_routes)) active @endif"><i class="mdi mdi-album"></i> <span>{{__('admin.end_user_menu')}}</span> <span class="float-right"><i class="mdi mdi-plus"></i></span></a>
                            <ul class="list-unstyled">
                                @can('viewAny', \App\Models\User::class)
                                <li class="@if(menu_active(['admin.users.index', 'admin.users.*'])) active @endif">
                                    <a href="{{route('admin.users.index')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.users')}}
                                    </a>
                                </li>
                                @endcan

                                <li class="@if(menu_active(['admin.login_attempts', 'admin.login_attempts'])) active @endif">
                                    <a href="{{route('admin.login_attempts')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.login_attempts')}}
                                    </a>
                                </li>

                                @can('viewAny', \App\Models\Book::class)
                                <li class="@if(menu_active(['admin.books.index', 'admin.books.*'])) active @endif">
                                    <a href="{{route('admin.books.index')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.books')}}
                                    </a>
                                </li>
                                @endcan
{{--                                @can('viewAny', \App\Models\BookPublishRequest::class)--}}
{{--                                <li class="@if(menu_active(['admin.publish_requests.index', 'admin.publish_requests.*'])) active @endif">--}}
{{--                                    <a href="{{route('admin.publish_requests.index')}}">--}}
{{--                                        <i class="mdi mdi-account-card-details"></i>--}}
{{--                                        {{__('global.publish_requests')}}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                @endcan--}}
                                @can('viewAny', \App\Models\Occupation::class)
                                <li class="@if(menu_active(['admin.occupations.index', 'admin.occupations.*'])) active @endif">
                                    <a href="{{route('admin.occupations.index')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.occupations')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Consult::class)
                                <li class="@if(menu_active(['admin.consults.index', 'admin.consults.*'])) active @endif">
                                    <a href="{{route('admin.consults.index')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.consults')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Blog::class)
                                <li class="@if(menu_active(['admin.blogs.index', 'admin.blogs.*'])) active @endif">
                                    <a href="{{route('admin.blogs.index')}}">
                                        <i class="mdi mdi-account-card-details"></i>
                                        {{__('global.blog')}}
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @php
                        $management_routes = ['admin.settings.index', 'admin.settings.*',
                        'admin.pages.index', 'admin.pages.*',
                        'admin.faqs.index', 'admin.faqs.*',
                        'admin.guides.index', 'admin.guides.*',
                        'admin.subscriptions.index', 'admin.subscriptions.*',
                        'admin.newsletter_emails.index', 'admin.newsletter_emails.*',
                        'admin.social_links.index', 'admin.social_links.*',
                        'admin.settings.tracingEmail',
                        'admin.contact_messages.index', 'admin.contact_messages.*', ];
                        @endphp
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect @if(menu_active($management_routes)) active @endif">
                                <i class="mdi mdi-album"></i> <span>{{__('admin.management_menu')}}</span> <span class="float-right"><i class="mdi mdi-plus"></i></span></a>
                            <ul class="list-unstyled">
                                @can('viewAny', \App\Models\Setting::class)
                                <li class="@if(menu_active(['admin.settings.index', 'admin.settings.*'])) active @endif">
                                    <a href="{{route('admin.settings.index')}}">
                                        <i class="mdi mdi-cogs"></i>
                                        {{__('global.settings')}}
                                    </a>
                                </li>
                                @endcan
                                @can('viewAny', \App\Models\Genre::class)
                                <li class="@if(menu_active(['admin.genres.index', 'admin.genres.*'])) active @endif">
                                    <a href="{{route('admin.genres.index')}}">
                                        <i class="mdi mdi-library-books"></i>
                                        {{__('global.genres')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Page::class)
                                <li class="@if(menu_active(['admin.pages.index', 'admin.pages.*'])) active @endif">
                                    <a href="{{route('admin.pages.index')}}">
                                        <i class="mdi mdi-google-pages"></i>
                                        {{__('global.pages')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Faq::class)
                                <li class="@if(menu_active(['admin.faqs.index', 'admin.faqs.*'])) active @endif">
                                    <a href="{{route('admin.faqs.index')}}">
                                        <i class="mdi mdi-comment-question-outline"></i>
                                        {{__('global.faqs')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Guide::class)
                                <li class="@if(menu_active(['admin.guides.index', 'admin.guides.*'])) active @endif">
                                    <a href="{{route('admin.guides.index')}}">
                                        <i class="mdi mdi-help"></i>
                                        {{__('global.guides')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Subscription::class)
                                <li class="@if(menu_active(['admin.subscriptions.index', 'admin.subscriptions.*', 'admin.newsletter_emails.index', 'admin.newsletter_emails.*'])) active @endif">
                                    <a href="{{route('admin.subscriptions.index')}}">
                                        <i class="mdi mdi-account-multiple"></i>
                                        {{__('global.subscriptions')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\SocialLink::class)
                                <li class="@if(menu_active(['admin.social_links.index', 'admin.social_links.*'])) active @endif">
                                    <a href="{{route('admin.social_links.index')}}">
                                        <i class="mdi mdi-account-multiple"></i>
                                        {{__('global.social_links')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\ContactMessage::class)
                                <li class="@if(menu_active(['admin.contact_messages.index', 'admin.contact_messages.*'])) active @endif">
                                    <a href="{{route('admin.contact_messages.index')}}">
                                        <i class="mdi mdi-account-multiple"></i>
                                        {{__('global.contact_messages')}}
                                    </a>
                                </li>
                                @endcan

                                @can('viewAny', \App\Models\Setting::class)
                                    <li class="@if(menu_active(['admin.settings.tracingEmail'])) active @endif">
                                        <a href="{{route('admin.settings.tracingEmail')}}">
                                            <i class="mdi mdi-mailbox"></i>
                                            {{__('admin.tracing_emails.title')}}
                                        </a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div> <!-- end sidebarinner -->
        </div>
        <!-- Left Sidebar End -->

        <!-- Start right Content here -->
        <div class="content-page">
            <div class="content">
                <div class="">
                    <div class="page-header-title">
                        <h4 class="page-title">@yield('header_title')</h4>
                    </div>
                </div>
                <div class="page-content-wrapper ">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                <footer class="footer">
                    © {{date('Y')}} {{__('global.short_title')}} - {{__('admin.rights_reserved')}}.
                </footer>
            </div>
        </div>
    </div>

    <script src="{{asset('js/admin/jquery.min.js')}}"></script>
    <script src="{{asset('js/admin/popper.min.js')}}"></script>
    <script src="{{asset('js/admin/bootstrap'.(LaravelLocalization::getCurrentLocaleDirection() == 'rtl'?'.rtl':'').'.min.js')}}"></script>
    <script src="{{asset('js/admin/modernizr.min.js')}}"></script>
    <script src="{{asset('js/admin/detect.js')}}"></script>
    <script src="{{asset('js/admin/fastclick.js')}}"></script>
    <script src="{{asset('js/admin/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('js/admin/jquery.blockUI.js')}}"></script>
    <script src="{{asset('js/admin/waves.js')}}"></script>
    <script src="{{asset('js/admin/wow.min.js')}}"></script>
    <script src="{{asset('js/admin/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/admin/jquery.scrollTo.min.js')}}"></script>
    <script src="{{asset('plugins/morris/morris.min.js')}}"></script>
    <script src="{{asset('plugins/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
    <script src="{{asset('js/admin/bootstrap4-toggle.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
    <script src="{{asset('js/admin/app.js')}}"></script>
    @include('admin.inc.axiosinit')
    @yield('scripts')
</body>
