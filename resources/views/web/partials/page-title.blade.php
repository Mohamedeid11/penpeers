@section('page_title')
<header class="ht__bradcaump__area author-header bg-image {{$background}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bradcaump__inner text-center">
                    <h2 class="bradcaump-title">{!! $title !!}</h2>
                    @if(isset($sub_title))
                        <nav class="bradcaump-content">
                            <a class="breadcrumb_item" href="{{ route('web.get_landing') }}">{{__('global.home')}}</a>
                            <span class="brd-separetor">/</span>
                            <span class="breadcrumb_item active">{!! $sub_title !!}</span>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
@endsection