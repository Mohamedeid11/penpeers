@php

    $accessKey = env('AccessKey');
    $environmentId = env('EnvironmentId');
    $payload = [
        'aud' => $environmentId,
        'iat' => time(),
    ];

    $token = Firebase\JWT\JWT::encode($payload, $accessKey, 'HS256');

@endphp
<header class="container-fluid d-flex flex-column">
    <div class="row page-title-div justify-content-between align-items-start flex-fill">
        <div class="col-sm-6">
            <a href="{{ isset($back_url)? $back_url : url()->previous() }}">
                @if(locale() == 'ar')
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                @else
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                @endif
                {{__('global.back')}}
            </a>
            <h2 class="title">{{$book->trans('title')}}</h2>
            <p class="sub-title">{{dashboardPageTitle()}}</p>
        </div>
        <div class="mt-3">
            <a class="btn btn-primary d-block"
                href="{{ route('web.dashboard.preview_book', ['book'=>$book, 'edition'=>'1']) }}#edition-content"><i class="fa-solid fa-eye"></i> {{ __('global.view_all_chapters') }}</a>

ِ{{--            <button class="btn btn-primary mt-1 d-block w-100" onclick="downloadPDF()"><i class="fa-solid fa-download"></i>--}}
{{--                {{ __('global.download_all_chapters') }}</button>--}}

            <button class="btn btn-primary mt-1 d-block w-100" onclick="download()"><i class="fa-solid fa-download"></i>
                {{ __('global.download_all_chapters') }}</button>
        </div>
    </div>
    <!-- /.row -->
    <div class="row breadcrumb-div">
        <div class="col-sm-6">
            <ul class="breadcrumb">
                <li><a href="{{route('web.get_landing')}}"><i class="fa-solid fa-home"></i> {{__('global.home')}}</a></li> /
                <li><a href="{{route('web.dashboard.index')}}">{{__('global.dashboard')}}</a></li> /
                <li><a href="{{route('web.dashboard.books.index')}}">{{__('global.my_books')}}</a></li> /
                @foreach($menu as $item)
                    <li @if($item['type'] == 'active') class="active" @endif>
                        @if($item['type'] != 'active')
                            <a href="{{$item['url']}}">{{$item['title']}}</a>
                        @else
                            {{$item['title']}}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</header>
@include('web.partials.flashes')
<script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
<script>
    const download = () => {
        swal({
            title: "{{ __('global.download_modal') }}",
            type: "info",
            confirmButtonClass: "btn-primary",
            confirmButtonText: "{{__('global.ok')}}",
            closeOnConfirm: true,
            showCancelButton: true,
            cancelButtonClass: "btn-outline-primary",
            cancelButtonText: "{{__('global.cancel')}}"
        }, async function(confirm) {
            if (confirm) {
                const url = "{{route('download.chapter' , ['book' => $book->id])}}";
                await axios.get( url );

            }
        });

    }




    {{--const downloadPDF = () => {--}}
    {{--    swal({--}}
    {{--        title: "{{ __('global.download_modal') }}",--}}
    {{--        type: "info",--}}
    {{--        confirmButtonClass: "btn-primary",--}}
    {{--        confirmButtonText: "{{__('global.ok')}}",--}}
    {{--        closeOnConfirm: true,--}}
    {{--        showCancelButton: true,--}}
    {{--        cancelButtonClass: "btn-outline-primary",--}}
    {{--        cancelButtonText: "{{__('global.cancel')}}"--}}
    {{--    }, async function(confirm) {--}}
    {{--        if (confirm) {--}}
    {{--            const bookTitle = "{{$book->title}}";--}}
    {{--            const token = "{{$token}}";--}}

    {{--            let data = {--}}
    {{--                html: `<div class='ck-content'>@include('web.partials.book-pdf-html')</div>`,--}}
    {{--                css: `@include('web.partials.book-pdf-styles')`,--}}
    {{--                options: {--}}
    {{--                    margin_top: "2cm",--}}
    {{--                    margin_bottom: "3cm",--}}
    {{--                    margin_left: "2cm",--}}
    {{--                    margin_right: "2cm",--}}
    {{--                    format: "A4",--}}
    {{--                    footer_html: `--}}
    {{--                        <div style="text-align: center; font-size: 10px; margin-bottom: 10mm">--}}
    {{--                            <p> -<span class="pageNumber"></span>- </p>--}}
    {{--                            <p> PenPeers </p>--}}
    {{--                        </div>`,--}}
    {{--                },--}}
    {{--            };--}}

    {{--            const config = {--}}
    {{--                headers: {--}}
    {{--                    Authorization: token,--}}
    {{--                },--}}
    {{--                responseType: "arraybuffer",--}}
    {{--            };--}}

    {{--            const response = await axios.post(--}}
    {{--                "https://pdf-converter.cke-cs.com/v1/convert",--}}
    {{--                data,--}}
    {{--                config--}}
    {{--            );--}}

    {{--            const link = document.createElement('a');--}}
    {{--            link.style.display = 'none';--}}
    {{--            document.body.appendChild( link );--}}

    {{--            const blob = new Blob([response.data], { type: 'text/plain' } );--}}
    {{--            const objectURL = URL.createObjectURL( blob );--}}

    {{--            link.href = objectURL;--}}
    {{--            link.href = URL.createObjectURL( blob );--}}
    {{--            link.download = `${bookTitle}.pdf`;--}}
    {{--            link.click();--}}
    {{--        }--}}
    {{--    });--}}

    {{--}--}}
</script>
