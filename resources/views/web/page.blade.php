@extends("web.layouts.master")
@section('heads')
<link rel="stylesheet" href="{{ asset('css/web/overview.css') }}">
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--3', 'title' => $name, 'sub_title' => $name])
@section('content')
    @if ($page->content)
        <div class="text-justify">
            {!! html_entity_decode($page->content) !!}
        </div>
    @else
        <div class="container text-center m-5">
            <h1>This is the {{$name}} page</h1>
        </div>
    @endif
@endsection
