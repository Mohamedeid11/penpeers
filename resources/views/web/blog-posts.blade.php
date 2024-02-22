@extends("web.layouts.master")
@section('heads')
<link rel="stylesheet" href="{{asset('css/web/blogs.css')}}">
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--4', 'title' => __('global.blog_page'), 'sub_title' => __('global.blog')])
@section('content')
<div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
    <div class="container">
        <!-- Blogs -->
        <main class="mx-auto">
            <div class="blog-page">
                <div class="page__header">
                    <h2>{{__('global.blog')}}</h2>
                </div>
                @foreach($posts as $post)
                    <!-- Start Single Post -->
                    <article class="blog__post d-flex flex-wrap">
                        @include('web.partials.post-card')
                    </article>
                    <!-- End Single Post -->
                @endforeach
                <div class="row justify-content-center mt-5">
                    <ul class="">
                        {{ $posts->links() }}
                    </ul>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
@section("scripts")
<script>
    // Format dates
    document.querySelectorAll(".post-date").forEach(item => {
        const dateTime = new Date(`${item.textContent}+00:00`);
        const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });

        item.textContent = date;
    })
</script>
@endsection
