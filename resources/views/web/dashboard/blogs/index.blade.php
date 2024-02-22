@extends('web.layouts.dashboard')
@section('heads')
    @include('web.partials.datatables-css')
    <link rel="stylesheet" href="{{asset('css/web/dashboard-books.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/web/buying-requests.css') }}" />
@endsection
@section('content')
    <main class="main-page">
        @include('web.partials.dashboard-header', ['title' => __('global.my_blog'), 'sub_title' => __('global.this_is_blog_posts'), 'current' => '<li class="active">'.__('global.my_blog').'</li>'])
        @include('web.partials.flashes')

        <section class="section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 d-flex mb-4">
                        <a class="btn btn-lg btn-primary ml-auto rounded-0"
                            href="{{ route('web.dashboard.blogs.create') }}">
                            <i class="fa-solid fa-square-plus"></i> {{ __('global.create_post') }}</a>
                    </div>
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h2 class="h5">{{ __('global.my_blog_list') }}</h2>
                                </div>
                            </div>
                            <div class="panel-body p-20" >
                                <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__('global.title')}}</th>
                                        <th>{{__('global.description')}}</th>
                                        <th>{{__('global.date')}}</th>
                                        <th>{{ __('global.actions') }}</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    @foreach($posts->sortByDesc('created_at') as $post)
                                        <tr>
                                            <td class="dt-control"></td>
                                            <td>
                                                @if($post->approved == 1)
                                                    <a href="{{route('web.blog_post',['id'=> $post->id])}}" class="text-underline text-secondary">{{$post->title}}</a>
                                                @else
                                                        {{$post->title}}
                                                @endif
                                            </td>
                                            <td class="buy-message">{{ Str::limit($post->description, 50) }}</td>
                                            <td><span class="blog-date">{{$post->created_at}}</span></td>
                                            <td class="d-flex flex-wrap" style="gap: 4px">
                                                <a class="btn btn-primary" href="{{route('web.dashboard.blogs.edit', ['blog'=>$post->id])}}" title="{{__('global.edit_post')}}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <form class="post-delete d-inline-block" action="{{route('web.dashboard.blogs.destroy', ['blog'=>$post->id])}}" method="POST">
                                                    {{ csrf_field() }}
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        title="{{ __('global.delete_post') }}"><i class="fa-solid fa-trash-can" style="width: 16px"></i></button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
@section('scripts')
    @include('web.partials.datatable')
    <script>

        // Bigger image
        let bookCoverImgs = document.querySelectorAll(".book-cover img");

        bookCoverImgs.forEach(img => {
            img.addEventListener("mouseenter", () => {
                const biggerImage = document.createElement("img");
                biggerImage.src = img.src;
                biggerImage.classList.add("book-cover-bigger-img");
                img.parentElement.appendChild(biggerImage);
            });

            img.addEventListener("mouseleave", () => {
                const biggerImage = document.querySelector(".book-cover-bigger-img");
                if(biggerImage)
                    biggerImage.parentElement.removeChild(biggerImage);
            });
        })

        // Delete blog
        $(document).on('submit', '.post-delete', function () {
            event.preventDefault();
            let form = $(this);
            swal(
            {
                title:"Delete this post ?",
                type:"error",
                showCancelButton:!0,
                confirmButtonClass:"btn-danger",
                confirmButtonText:"Yes, Do it!",
            }, function (confirm) {
                if (confirm) {
                    form.submit();
                }
            });
        });

        // Format dates
        document.querySelectorAll(".blog-date").forEach(item => {
            const dateTime = new Date(`${item.textContent}+00:00`);
            const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
            const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });

            item.textContent = `${date}, ${time}`;
        })

        $('.table tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = datatable.row(tr);
            if (row.child.isShown()) {
                const dateTime = new Date(`${$(tr).next().find(".blog-date").text()}+00:00`);
                const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
                const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });

                $(tr).next().find(".blog-date").text(`${date}, ${time}`)
            }
        });
    </script>
@endsection
