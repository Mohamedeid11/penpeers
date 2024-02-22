<footer id="wn__footer" class="footer__area bg__cat--8 brown--color">
    <div class="footer-static-top">
        <div class="container">
            <div class="footer__menu">
                <div class="ft__logo">
                    <a href="{{route('web.get_landing')}}">
                        <img src="{{asset('images/web/logo/logo2.png')}}" alt="logo" style="width:100px;">
                    </a>
                    <p>{{__('global.follow_penpeers')}}</p>
                </div>
                <div class="footer__content">
                    <ul class="social__net social__net--2 d-flex justify-content-center">
                        @foreach ($social_links as $link)
                            <li><a href="{{$link['link']}}" target="_blank"><i class="{{$link['icon']}}"></i></a></li>
                        @endforeach
                    </ul>
                    <nav>
                        <ul class="mainmenu d-flex justify-content-center flex-wrap">
                            <li><a href="{{route('web.get_terms')}}">{{__('global.global_pages.terms')}}</a></li>
                            <li><a href="{{route('web.get_how_it_works')}}">{{__('global.guide')}}</a></li>
                            <li><a href="{{route('web.get_about')}}">{{__('global.global_pages.about')}}</a></li>
                            <li><a href="{{route('web.books')}}">{{__('global.books')}}</a></li>
                            <li><a href="{{route('web.authors')}}">{{__('global.authors')}}</a></li>
                            <li><a href="{{route('web.consult')}}">{{__('global.consult')}}</a></li>
                            <li><a href="{{route('web.blog_posts')}}">{{__('global.blog')}}</a></li>
                            <li><a href="{{route('web.get_how_it_works')}}">{{__('global.faq_short')}}</a></li>
                            <li><a href="{{route('web.get_contact')}}">{{__('global.contact')}}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright__wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="copyright">
                        <div class="copy__right__inner">
                            <p>{{ __('global.copyright') }} &copy; {{__('global.onAirCommerce_product')}} <a href="{{route('web.get_landing')}}"> {{__('global.penpeers')}}</a>. {{__('global.allrights_reserved')}} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
