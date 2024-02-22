@extends('web.layouts.dashboard')
@section('heads')
    <link rel="stylesheet" href="{{asset('css/web/cropper.min.css')}}">
@endsection
@section('content')
    <main class="main-page">
        @php
            $route = route("web.dashboard.blogs.index");
        @endphp
        @include('web.partials.dashboard-header', ['title' => __('global.my_blog'), 'sub_title' => __('global.edit_your_post'), 'current' => '<li><a href="'. $route .'">'.__('global.my_posts').'</a></li> /<li class="active">'.__('global.edit_post').'</li>'])
        <section class="section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h2 class="underline h5">{{__('global.edit_post')}}</h2>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form enctype="multipart/form-data" method="POST" action="{{route('web.dashboard.blogs.update', ['blog' => $post->id])}}">
                                    {{@csrf_field()}}
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="title-input">{{__('global.post_title')}}<sup class="color-danger">*</sup></label>
                                                <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title-input" name="title"
                                                        placeholder="{{__('global.enter_post_title')}}" data-validation="required" value="{{$post->title}}" required>
                                                @if($errors->has('title'))
                                                    <div class="invalid-feedback">
                                                        @foreach($errors->get('title') as $error)
                                                            {{$error}}<br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description-input">{{__('global.post_description')}}<sup class="color-danger">*</sup></label>
                                                <textarea class="form-control @if($errors->has('description')) is-invalid @endif" id="description-input"
                                                        placeholder="{{__('global.enter_post_description')}}"
                                                        name="description" required>{{$post->description}}</textarea>
                                                @if($errors->has('description'))
                                                    <div class="invalid-feedback">
                                                        @foreach($errors->get('description') as $error)
                                                            {{$error}}<br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            @include('web.partials.crop-file-input', ['name'=>'image', 'display_name'=>__('global.image'), 'default_img' => 'defaults/front.png'])
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn bg-black btn-wide ml-auto d-block"><i class="fa-solid fa-arrow-right"></i> {{__('global.submit')}} </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br>
    </main>
@endsection
@section('scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(`#image-preview img`).attr("src", e.target.result);
                };
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(".crop-file-input").on("input", function(e) {
            let preview_div = $(this).prev($('.crop-preview')).first();
            let data_target = preview_div.attr('data-target');
            readURL(this);
            if (e.target.files[0]?.name) {
                e.currentTarget.nextElementSibling.querySelector("span").innerHTML = e.target.files[0]?.name;

                document.querySelector(`.revert-image`).classList.remove("d-none");
                document.querySelector(`[id$=_revertToDefaultImage]`).checked = false;
            }
        })

        // Revert to default image
        document.querySelectorAll(`[id$=_revertToDefaultImage]`).forEach(inp => {
            inp.addEventListener("change", (e) => {
                const name = e.target.id.split("_").slice(0, -1).join("_");
                
                if(e.target.checked) {
                    document.querySelector(`.crop-file-input[name='${name}']`).value = ""
                    document.querySelector(`[name='${name}_crop']`).value = ""
                    document.querySelector(`label[for='${name}-input'] span`).innerHTML =
                    "{{ __('admin.placeholder_text', ['name'=>__('global.image')]) }}";
                    document.querySelector(`.revert-image.${name}_revert`).classList.add("d-none")
                    e.target.previousElementSibling.src = e.target.dataset.default;
                }
            })
        });
    </script>
@endsection
