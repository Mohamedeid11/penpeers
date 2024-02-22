<div class="thumb">
    <a
        href="{{ route('web.blog_post', ['id' => $post->id]) }}">
        <img src="{{ storage_asset($post->image) }}" alt="blog images">
    </a>
</div>
<div class="content">
    <h4><a
            href="{{ route('web.blog_post', ['id' => $post->id]) }}">{{ $post->title }}</a>
    </h4>
    <ul class="post__meta">
        <li>{{ __('global.posts_by') }} <a
                href="{{ route('web.author_books', ['author' => $post->user->id, 'type'=> 'all_books' ]) }}">{{ $post->user->name }}</a>
        </li>
        <li class="post_separator">/</li>
        <li class="post-date">{{ $post->created_at }}</li>
    </ul>
    <p>{{ $post->description }}</p>
    <div class="blog__btn">
        <a
            href="{{ route('web.blog_post', ['id' => $post->id]) }}">{{ __('global.read_more') }}</a>
    </div>
</div>