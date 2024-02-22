@extends("web.layouts.master")
@section('heads')
<link rel="stylesheet" href="{{asset('css/web/faq.css')}}">
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--4', 'title' => __('global.guide')." / ".__('global.faq_short'), 'sub_title' => __('global.guide')])
@section('content')
    <main class="wn__faq__area bg--white pt--80 pb--60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <header class="wn__accordeion__content">
                        <a href="{{asset('guide.pdf')}}" class="btn btn-primary mb-3" target="_blank">{{__('global.view_user_guide')}}</a>
                        <a href="{{asset('editor-guide.pdf')}}" class="btn btn-outline-primary mb-3 ml-2" target="_blank">{{__('global.view_editor_guide')}}</a>
                        <h2>{{ __('global.faq_subheading') }}</h2>
                    </header>

                    <dl id="accordion" class="wn_accordion" role="tablist">
                        @foreach ($guides as $guide)
                        <div class="card">
                            <dt class="acc-header" role="tab" id="headingOne">
                                <button 
                                    data-toggle="collapse" 
                                    data-target="#collapseOne-{{$guide->id}}" 
                                    role="button" 
                                    aria-expanded="false" 
                                    class="collapsed"
                                    aria-controls="collapseOne">{{$guide->name}}</button>
                            </dt>
                            <dd id="collapseOne-{{$guide->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne"
                                data-parent="#accordion">
                                <p class="card-body">
                                    {!! $guide->explanation !!}
                                </p>
                            </dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
    </main>
@endsection
