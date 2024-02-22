<!doctype html>
<html class="no-js" lang="{{ locale() }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PenPeers</title>
    <meta name="description" property="og:description" content="The first platform for authors and co-authors" />
    <meta property="og:title" content="PenPeers"/>
    <meta property="og:url" content="{{route('web.get_landing')}}" />
    <meta property="og:image" content="{{asset('images/web/logo/logo-seo.png')}}" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:alt" content="PenPeers logo" />
    <meta property="og:type" content="books.book" />

    <meta name="twitter:card" content="summary" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="shortcut icon" href="{{asset('images/web/logo/favicon.png')}}">
    <link rel="apple-touch-icon" href="{{asset('images/web/logo/favicon.png')}}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{asset('css/web/'.ldir().'/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/web/plugins.css')}}">
    <link rel="stylesheet" href="{{asset('css/web/style.css')}}">
    <link rel="stylesheet"
          href="{{ asset('css/web/'.ldir().'/style.css') }}" type="text/css"
          media="all" />
    {!! htmlScriptTagJsApi() !!}
    @yield('heads')
</head>

<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<!-- Main wrapper -->
<div class="wrapper" id="wrapper">
    <!-- Header -->
    @include('web.partials.header')
    <!-- //Header -->
    @yield('page_title')
    @yield('content')
    <!-- Footer Area -->
    @include('web.partials.footer')
    <!-- //Footer Area -->

</div>
@if(Auth::check() && Auth::user()->email_verified_at == null)
    <nav class="navbar fixed-bottom navbar-dark bg-dark opacity--7">
        <div class="container">
            <p class="text-light">{{__('global.verify_email_message')}} <a
                    class="btn text-danger btn-link"
                    href="{{route('verification.notice')}}">{{ __('global.resend_verification') }}</a>
            </p>
        </div>
    </nav>

@endif
<!-- //Main wrapper -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="{{asset('js/web/vendor/modernizr-3.5.0.min.js')}}"></script>
<script src="{{asset('js/web/plugins.js')}}"></script>
<script>
    const lang = "{{locale()}}";
    const navText = ['<i class="zmdi zmdi-chevron-left"></i>', '<i class="zmdi zmdi-chevron-right"></i>' ];
    if(lang === "ar") navText.reverse();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    window.addEventListener('load', (event) => {
        const recaptchas = document.querySelectorAll('[id^=g-recaptcha-response]');

        if (recaptchas.length) {
            recaptchas.forEach((recaptcha, i) => {
                recaptcha.setAttribute("required", "required");
                recaptcha.oninvalid = function(e) {
                    document.querySelectorAll(".recaptcha-error")[i].classList.remove("d-none")
                }
            })
        }
    });
</script>
<script src="{{asset('js/web/active.js')}}"></script>
@yield('scripts')
</body>
</html>
