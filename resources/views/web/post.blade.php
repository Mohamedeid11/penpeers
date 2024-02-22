@extends("web.layouts.master")
@section('heads')
<link rel="stylesheet" href="{{asset('css/web/blog.css')}}"/>
<link rel="stylesheet" href="{{asset('css/web/book.css')}}"/>
<link rel="stylesheet" href="{{ asset('css/web/likely.css') }}" />
<link rel="stylesheet" href="{{ asset('css/web/likely-custom.css') }}" />
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--4', 'title' => __('global.blog_details'), 'sub_title' => __('global.blog_details')])
@section('content')
    @include('web.partials.flashes')
    <div class="page-blog-details section-padding--lg bg--white">
        <div class="container">
            <div class="row">
                <!-- Recent posts -->
                @include('web.partials.recent-blogs')

                <!-- Post details -->
                <main class="col-lg-9 col-12 order-1 order-lg-2 blog-details content">
                    <article class="blog-post-details row product__info__detailed">
                        <div class="col-lg-6 col-12">
                            <!-- Post image -->
                            <img class="post-thumbnail" id="img_url" src="{{ storage_asset($post->image) }}"
                                alt="blog images" data-no-retina="">
                        </div>

                        <div class="col-lg-6 col-12 post_wrapper">
                            <!-- Share links -->
                            <div class="post_header">
                                <h2>{{ $post->title }}</h2>

                                <div class="blog-date-categori">
                                    <ul class="d-flex justify-content-between">
                                        <li id="cdate">{{ $post->created_at }}</li>
                                        <li><a href="{{ route('web.author_books', ['author' => $post->user->id, 'type'=> 'all_books' ]) }}"
                                                class="authorname" rel="author">{{ $post->user->name }}</a></li>
                                    </ul>
                                </div>
                                @php
                                    $shareUrl = LaravelLocalization::getNonLocalizedURL(route('web.blog_post', ['id'=> $post->id]))
                                @endphp
                                <div
                                    class='likely text-center section_tittle pt-4 w-100 d-flex flex-wrap justify-content-center'
                                    data-url="{{ $shareUrl }}">
                                    <div class='facebook' data-url="{{ $shareUrl }}" title="{{__('global.share_facebook')}}"></div>
                                    <div class='twitter' data-url="{{ $shareUrl }}"
                                        title="{{ __('global.share_twitter') }}"></div>
                                    <div class='linkedin' data-url="{{ $shareUrl }}"
                                        title="{{ __('global.share_linkedin') }}"></div>
                                    <div class='telegram' data-url="{{ $shareUrl }}"
                                        title="{{ __('global.share_telegram') }}"></div>
                                    <div class='whatsapp' data-url="{{ $shareUrl }}"
                                        title="{{ __('global.share_whatsapp') }}"></div>
                                    {{-- <div class='likely__widget'
                                        title="{{ __('global.share_email') }}">
                                        <button class="likely__button btn-clip likely-icon"
                                                data-toggle="modal"
                                                data-target="#share-to-email-modal"
                                                id="add-edition-trigger"
                                                data-placement="top" type="button">
                                            <img class='likely__icon likely-icon'
                                                alt="{{ __('global.share_email') }}"
                                                src='<?=asset("images/img/mail.png")?>'>
                                        </button>
                                    </div> --}}
                                    <div class='likely__widget likely-copy'
                                        title="{{ __('global.copy_url') }}">
                                        <button class="likely__button btn-clip likely-icon"
                                                onclick="copyShareURL(this, '{{ $shareUrl }}')"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                type="button">
                                            <img class='likely__icon likely-icon'
                                                alt="{{ __('global.copy_url') }}"
                                                    src='<?=asset("images/img/copy-icon.svg")?>'>
                                        </button>
                                    </div>
                                    <span class="likely__widget border-0" style="width: 20px">{{__('global.or')}}</span>
                                    <div class='likely__widget likely-mob'
                                        title="{{ __('global.mobile_only') }}">
                                        <a class='likely__button answer-example-share-button likely-icon text-white'
                                            href='javascript:;'
                                            data-url="{{ $shareUrl }}">
                                            <img class='likely__icon likely-icon'
                                                alt="{{ __('global.mobile_only') }}"
                                                    src='<?=asset("images/img/mobile.png")?>'>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <nav class="pro_details_nav nav justify-content-start col-12 pl-3" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#nav-details"
                                role="tab">{{ __('global.read_feed') }}
                            </a>
                            <a class="nav-item nav-link" data-toggle="tab" href="#nav-comments" role="tab">{{ __('global.comments') }}</a>
                        </nav>

                    </article>

                    <div class="collapse post_content b_content pro__tab_label tab-pane fade show active" id="nav-details"
                        role="tabpanel">
                        {{ $post->description }}</p>
                    </div>

                    <div id="nav-comments" class="collapse pro__tab_label tab-pane fade" role="tabpanel">

                        <!-- For each comment -->
                        <div class="comments_area">
                            @foreach($post_comments as $comment)
                                <!-- Comment -->
                                <div class="wn__comment">
                                    <div class="thumb">
                                        <img src="{{storage_asset($comment->user->profile_pic)}}"
                                                alt="comment images">
                                    </div>
                                    <div class="content">
                                        <div class="comnt__author d-block d-sm-flex flex-column mb-3">
                                            <a href="{{route('web.author_books', ['author' => $comment->user->id, 'type'=> 'all_books' ])}}">{{$comment->user->name}}</a>
                                            <span class="comment-date">{{$comment->created_at}}</span>
                                        </div>
                                        <p>{{$comment->comment}}</p>
                                        <a class="reply__btn" data-toggle="collapse" href="#write-reply-{{$comment->id}}"
                                            role="button" aria-expanded="false"
                                            aria-controls="write-reply-{{$comment->id}}">>{{__('global.reply')}}</a>
                                    </div>
                                </div>

                                <!-- Write a reply -->
                                <div class="comment_respond collapse mb-5" id="write-reply-{{$comment->id}}">
                                    <h3 class="reply_title">{{__('global.leave_reply')}}</h3>
                                    <form class="comment__form" method="POST"
                                            action="{{route('web.dashboard.blogs.comments', ['blog' => $post->id , 'comment' => $comment])}}">
                                        @csrf
                                        <textarea class="form-control mb-3" rows="4" name="comment"
                                                    placeholder="{{ __('global.reply_here') }}"></textarea>
                                        <input type="hidden" name="type" value="reply"/>
                                        <button href="#" class="btn btn-secondary">{{__('global.submit')}}</button>
                                        <a class="btn btn-danger" data-toggle="collapse" href="#write-reply-{{$comment->id}}"
                                            role="button" aria-expanded="true" aria-controls="write-reply-{{$comment->id}}">{{__('global.cancel')}}</a>
                                    </form>
                                </div>

                                @foreach($comment->replies as $reply)
                                    <!-- Replies -->
                                    <div class="comment_reply wn__comment">
                                        <div class="thumb">
                                            <img src="{{storage_asset($reply->user->profile_pic)}}"
                                                    alt="comment images">
                                        </div>
                                        <div class="content">
                                            <div class="comnt__author d-block d-sm-flex flex-column mb-3">
                                                <a href="{{route('web.author_books', ['author' => $reply->user->id, 'type'=> 'all_books' ])}}">{{$reply->user->name}}</a>
                                                <span class="comment-date">{{$reply->created_at}}</span>
                                            </div>
                                            <p>{{$reply->comment}}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>


                        <!-- Write a comment -->
                        <div class="comment_respond">
                            <h3 class="reply_title">{{__('global.leave_comment')}}</h3>
                            <form class="comment__form" method="POST"
                                    action="{{route('web.dashboard.blogs.comments', ['blog' => $post->id])}}">
                                @csrf
                                <textarea class="form-control mb-3" rows="4" name="comment"
                                            placeholder="{{ __('global.comment_here') }}"></textarea>
                                <input type="hidden" name="type" value="comment"/>
                                <button href="#" class="btn btn-secondary">{{__('global.submit')}}</button>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
@include('web.partials.sharing_link_to_email_modal')
@section("scripts")
    <script src="{{ asset('js/web/likely.js') }}"></script>
    <script>
        // Format date
        // Blog date
        const blogDate = document.querySelector("#cdate");
        const blogDateTime = new Date(`${blogDate.textContent}+00:00`);
        blogDate.textContent = blogDateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric"});

        // Comments and replies date
        document.querySelectorAll(".comment-date").forEach(item => {
            const dateTime = new Date(`${item.textContent}+00:00`);
            const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
            const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });

            item.textContent = `${date}, ${time}`
        })

        // Recent blogs dates
        document.querySelectorAll(".post-date").forEach(item => {
            const dateTime = new Date(`${item.textContent}+00:00`);
            const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });

            item.textContent = `${date}`
        })

        const copyShareURL = (e, text) => {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            e.title = "{{__('global.copied')}}";
            $(e).tooltip("show");
        }

        $(document).on('click', '.answer-example-share-button', function () {
            const url = $(this).attr('data-url');
            if (navigator.share) {
                navigator.share({
                    title: '{{$post->title}} PenPeers Book',
                    text: 'Have a look at {{$post->title}} PenPeers Book',
                    url: url,
                })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
            } else {
                console.log('Share not supported on this browser, do it the old way.');
            }
        });
    </script>
@endsection
