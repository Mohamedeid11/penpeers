<aside class="col-lg-3 col-12 md-mt-40 sm-mt-40 order-2 order-lg-1">
    <div class="wn__sidebar">
        <div class="widget recent_widget">
            <h3 class="widget-title">{{__('global.recent_5_feeds')}}</h3>
            <div class="recent-posts">
                <ul>
                    @foreach($recent_posts as $blog)
                        <li class="post-wrapper d-flex">
                            <a class="thumb d-block"
                                href="{{ route('web.blog_post', ['id' => $blog->id]) }}">
                                <img src="{{ storage_asset($blog->image) }}" alt="blog images" class="h-100">
                            </a>
                            <p class="content">
                                <a
                                    href="{{ route('web.blog_post', ['id' => $blog->id]) }}">{{ $blog->title }}</a>
                                <span class="post-date d-block">{{ $blog->created_at }}</span>
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</aside>
