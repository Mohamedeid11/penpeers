<header class="container-fluid">
    <div class="row page-title-div">
        <div class="col-sm-6">
            <a href="{{ url()->previous() }}">
                @if(locale() == "ar")
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                @else
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                @endif
                {{__('global.back')}}
            </a>
            <h2 class="title">{{$title}}</h2>
            <p class="sub-title">{{$sub_title}}</p>
        </div>
    </div>

    <div class="row breadcrumb-div">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li><a href="{{ route('web.get_landing') }}"><i class="fa-solid fa-house"></i> {{__('global.home')}}</a></li>
                /
                <li><a href="{{ route('web.dashboard.index') }}">{{__('global.dashboard')}}</a></li> /
                {!! $current !!}
            </ul>
        </div>
        <!-- /.col-sm-6 -->
    </div>
    <!-- /.row -->
</header>