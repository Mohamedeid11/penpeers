<header id="wn__header" class="oth-page header__area header__absolute sticky__header">
    <div class="container-fluid">
        <div class="row">
            <!-- Logo -->
            <div class="col-6 col-xl-2">
                <div class="logo" style="z-index: 9999;">
                    <a href="{{route('web.get_landing')}}" class="d-block" style="width: max-content">
                        <img src="{{asset('images/web/logo/logo2.png')}}" alt="logo images" style="width:70px;margin-top:10px;">
                    </a>
                </div>
            </div>

            <div class="col-xl-9 d-none d-xl-block">
                <nav class="mainmenu__nav">
                    <ul class="meninmenu d-flex justify-content-start">
                        <li class="drop with--one--item"><a href="{{route('web.get_landing')}}">{{__('global.home')}}</a></li>
                        <li class="drop"><a href="{{route('web.books')}}">{{__('global.books')}}</a></li>
                        <li class="drop"><a href="{{route('web.authors')}}">{{__('global.authors')}}</a></li>
                        <li class="drop"><a href="{{route('web.consult')}}">{{__('global.consult')}}</a></li>
                        <li class="drop"><a href="{{route('web.blog_posts')}}">{{__('global.blog')}}</a></li>
                        <li class="drop"><a href="{{route('web.get_how_it_works')}}">{{__('global.faq_short')}}</a></li>
                        <li class="drop"><a href="{{route('web.get_about')}}">{{__('global.global_pages.about')}}</a></li>
                        <li class="drop"><a href="{{route('web.get_overview')}}">{{__('global.global_pages.overview')}}</a></li>
                        <li><a href="{{route('web.get_contact')}}">{{__('global.contact')}}</a></li>
                    </ul>
                </nav>
            </div>

            <div class="col-md-6 col-sm-6 col-6 col-xl-1">
                <ul class="header__sidebar__right d-flex justify-content-end align-items-center t">

                    @if(Auth::check())
                        <li class="nav-item dropdown">
                            <a  class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-bell text-light fa-lg"></i> @if(count(auth()->user()->unreadNotifications) > 0)   <span class="badge-pill text-warning text-lg-center notifications-count"> {{ count(auth()->user()->unreadNotifications)}}</span> @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="navbarDropdown" style="width: 81vw; max-width: 350px">
                                <ul class="notifications-menu">
                                    @if(count(auth()->user()->unreadNotifications) > 0)
                                        @foreach(auth()->user()->unreadNotifications as $notification)
                                            <li class=" align-items-center  dropdown-item p-0 ">
                                                <form action="{{route('notification.read')}}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="notification_id" value="{{$notification->id}}">
                                                    <input type="hidden" name="url" value="{{$notification['data']['url']}}">
                                                    <button type="submit" title="notify" class="d-flex align-items-center my-3 dropdown-item p-0">
                                                        <span class="rounded-circle mr-2 bell-icon">
                                                            <i class="fa-solid fa-bell"></i>
                                                        </span>
                                                        <span class="d-block flex-shrink-1 flex-grow-1 notification-text">
                                                            {!! $notification['data']['message'] !!}
                                                            <small class="w-100 d-block">{{\Carbon\Carbon::createFromTimeStamp(strtotime($notification['created_at']))->diffForHumans()}}</small>
                                                        </span>
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-center text-secondary font-weight-bold">
                                            No notifications
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a  class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-user text-light fa-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="navbarDropdown">
                            <ul>
                                <li>
                                    <form class="form dropdown-item">
                                        <div class="form-group m-0">
                                            <select class="form-control" onchange="location.href=$(this).find($('option[value='+$(this).val()+']')).attr('data-value');">
                                                @foreach(locales() as $key => $locale)
                                                    @if($key == 'en' || $key == 'ar')
                                                        <option data-value="{{lroute($key)}}" value="{{$key}}" @if($key == locale()) selected @endif>{{$locale['native']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </li>
                                @if(!Auth::check())
                                    <li>
                                        <a  class="dropdown-item" href="{{route('web.get_login')}}" title="Login">
                                            <i class="fa-solid fa-sign-in"></i> {{__('global.login')}}
                                        </a>
                                    </li>
                                @endif
                                @if(!Auth::check())
                                    <li>
                                        <a  class="dropdown-item" href="{{route('web.get_register')}}" title="Register">
                                            <i class="fa-solid fa-user-plus"></i> {{__('global.register')}}
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::check())
                                    <li>
                                        <a class="dropdown-item" href="{{route('web.dashboard.account.edit.show')}}">
                                            <i class="fa-solid fa-cog"></i> {{__('global.my_account')}}
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::check())
                                    <li>
                                        <a class="dropdown-item" href="{{route('web.dashboard.account.status')}}">
                                            <i class="fa-solid fa-cog"></i> {{__('global.my_account_status')}}
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::check() && Auth::user()->role->name != 'reader')
                                    <li>
                                        <a class="dropdown-item" href="{{route('web.dashboard.index')}}">
                                            <i class="fa-solid fa-user"></i> {{__('global.my_dashboard')}}
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::check())
                                    <li>
                                        <form id="logout-form" class="d-inline-block w-100" method="POST" action="{{route('web.post_logout')}}">{{@csrf_field()}}
                                            <a title="Logout" class="dropdown-item" href="javascript:document.getElementById('logout-form').submit();">
                                                <i class="fa-solid fa-lg fa-sign-out"></i>{{__('global.logout')}}
                                            </a>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Start Mobile Menu -->
        <div class="row d-none">
            <div class="col-lg-12 d-none">
                <nav class="mobilemenu__nav">
                    <ul class="meninmenu">
                        <li><a href="{{route('web.get_landing')}}">{{__('global.home')}}</a></li>
                        <li><a href="{{route('web.books')}}">{{__('global.books')}}</a></li>
                        <li><a href="{{route('web.authors')}}">{{__('global.authors')}}</a></li>
                        <li><a href="{{route('web.consult')}}">{{__('global.consult')}}</a></li>
                        <li><a href="{{route('web.blog_posts')}}">{{__('global.blog')}}</a></li>
                        <li><a href="{{route('web.get_how_it_works')}}">{{__('global.faq_short')}}</a></li>
                        <li><a href="{{route('web.get_about')}}">{{__('global.global_pages.about')}}</a></li>
                        <li><a href="{{route('web.get_overview')}}">{{__('global.global_pages.overview')}}</a></li>
                        <li><a href="{{route('web.get_contact')}}">{{__('global.contact')}}</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- End Mobile Menu -->
        <div class="mobile-menu d-block d-xl-none"  >
        </div>
        <!-- Mobile Menu -->
    </div>
</header>

<!-- notification toast -->
<!-- <div class="m-2 position-fixed" aria-live="polite" aria-atomic="true" style="top: 60px; right: 0; z-index: 1000">
    <div class="toast hide" id="notification-toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-primary">
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="d-flex flex-wrap">
                <span class="rounded-circle bell-icon">
                    <i class="fa fa-bell"></i>
                </span>
                <p class="flex-shrink-1 flex-grow-1 notification-text">
                    <span>You have a new buying request for Mystery of Universe book </span>
                    <small class="w-100 d-block">2 minutes ago</small>
                </p>
            </div>
        </div>
    </div>
</div> -->

<!-- this fo web socket -->
<!-- <script src="{{ asset('js/app.js') }}"></script> -->
<script src="{{ asset('js/web/vendor/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/web/popper.min.js') }}"></script>
<script src="{{ asset('js/web/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/web/fontawesome.js') }}"></script>
<script src="{{ asset('js/admin/bootstrap4-toggle.min.js') }}"></script>

<script>
    // $("#notification-toast").toast({
    //     delay: 10000
    // });

    // Echo
    //     .private('events')
    //     .listen('RealTimeMessage', (e) => alert('Private RealTimeMessage: ' + e.message));

    // Echo
    //     .private('App.Models.User.'  + {{ isset(auth()->user()->id) ? auth()->user()->id : ' ' }})
    //     .notification((notification) => {
    //         // Show notification
    //         document.querySelector(".notification-text span").innerHTML = notification.message;
    //         document.querySelector(".notification-text small").innerHTML = 'now';
    //         $("#notification-toast").toast("show");
    //         // Add notification to dropdown
    //         const dropdownMenu = document.querySelector(".notifications-menu");
    //         const liElm = document.createElement("li");
    //         liElm.classList.add("d-flex", "align-items-center", "my-3", "dropdown-item", "p-0");
    //         liElm.innerHTML = `<li>
    //             <form action="{{route('notification.read')}}" method="get">
    //                 {{csrf_field()}}
    //                 <input type="hidden" name="notification_id" value="${notification.id}">
    //                 <input type="hidden" name="url" value="${notification.url}">
    //                 <button type="submit" title="notify" class="d-flex align-items-center my-3 dropdown-item p-0">
    //                     <span class="rounded-circle mr-2 bell-icon">
    //                         <i class="fa fa-bell"></i>
    //                     </span>
    //                     <span class="d-block flex-shrink-1 flex-grow-1 notification-text">
    //                         ${notification.message}
    //                         <small class="w-100 d-block"> now </small>
    //                     </span>
    //                 </button>
    //             </form>
    //         </li>`;

    //         dropdownMenu.insertBefore(liElm, dropdownMenu.firstChild);
    //         // Add number of notifications
    //         const notificationsCountElm = document.querySelector(".notifications-count");
    //         const notificationsNum = +notificationsCountElm.innerHTML;
    //         notificationsCountElm.innerHTML = notificationsNum + 1;
    //         console.log(notification.url)
    //     });
</script>
