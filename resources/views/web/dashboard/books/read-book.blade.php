@extends("web.layouts.dashboard")
@section('heads')
<link rel="stylesheet" type="text/css" href="{{ asset('css/web/ck-editor-custom.css') }}">
@endsection
@section('content')
    <main class="main-page">
        @include('web.partials.dashboard-header', ['title' => $book->trans('title'), 'sub_title' => __('global.my_books_sub_heading'), 'current' => '<li class="active">'.__('global.my_books').'</li>'])

        <section class="section">
            <div class="container-fluid p-0 overflow-auto ck-editor-read">
                @include('web.partials.book-pdf')
            </div>
        </section>
    </main>
@endsection