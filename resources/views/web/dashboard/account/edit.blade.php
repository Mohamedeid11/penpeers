@extends('web.layouts.dashboard')
@section('heads')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="{{ asset('css/web/cropper.min.css') }}">
@endsection
@section('content')
<main class="main-page">
    @include('web.partials.dashboard-header', ['title' => __('global.edit_profile'), 'sub_title' => __('global.edit_your_profile'), 'current' => '<li class="active">'.__('global.my_account').'</li>'])
    @include('web.partials.flashes')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <!-- Edit Details -->
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h3 class="h5">{{ __('global.edit_details') }}</h3>
                            </div>
                        </div>

                        <div class="panel-body">
                            <form class="mt-3" action="{{route('web.dashboard.account.edit.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row w-100 mx-auto">
                                    <div class="mb-3 col-sm-6">
                                        <label for="name">{{__('global.name')}}*</label>
                                        <input class="mb-sm-0 form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="{{__('global.name')}}" required value="{{inp_value($user, 'name')}}" id="name" />
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="username">{{__('global.username')}}*</label>
                                        <input class="mb-sm-0 form-control  @error('username') is-invalid @enderror" type="text" name="username" placeholder="{{__('global.username')}}" value="{{inp_value($user, 'username')}}" id="username" required />
                                        @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="email">{{__('global.email')}}*</label>
                                        <input class="mb-sm-0 form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="{{__('global.email')}}" required value="{{inp_value($user, 'email')}}" id="email" disabled />
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label
                                            for="mobile_number">{{ __('global.mobile_number') }}*</label>
                                        <input class="mb-sm-0 form-control @error('mobile_number') is-invalid @enderror" type="tel" name="mobile_number" placeholder="mobile number" required value="{{inp_value($user, 'mobile_number')}}" id="mobile_number" />
                                    </div>
                                    @error('mobile_number')
                                    <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                    @enderror
                                    <div class="col-sm-6 mb-3">
                                        <label for="country">{{__('global.country')}}*</label>
                                        <select name="country_id" id="country" required class="mb-sm-0 form-control @error('country_id') is-invalid @enderror ">
                                            <option value="" disabled selected>{{__('global.select')}}</option>
                                            @foreach($countries as $country)
                                            <option value="{{$country->id}}" {{select_value($user, 'country_id', $country->id)}}>{{$country->trans('name')}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country_id')
                                    <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                    @enderror

                                    <div class="mb-3 col-sm-6">
                                        <label for="gender">{{__('global.gender')}}*</label>
                                        <select name="gender" id="gender" class="mb-sm-0 form-control @error('gender') is-invalid @enderror " required>
                                            <option value="" disabled selected>{{__('global.select')}}</option>
                                            <option value="m" {{select_value($user, 'gender', "m")}}>{{__('global.male')}}</option>
                                            <option value="f" {{select_value($user, 'gender', "f")}}>{{__('global.female')}}</option>
                                        </select>
                                        @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label for="interests">{{ __('global.interests') }}</label>
                                        <select name="interests[]"
                                            data-placeholder="{{ __('global.select_insterests') }}" id="interests"
                                            size="5"
                                            class="mb-sm-0 form-control chosen-select @if($errors->has('interests')) is-invalid @endif "
                                            multiple>
                                            @foreach($interests as $interest)
                                            <option value="{{$interest->id}}" @if(in_array($interest->id, $userInterests)) selected @endif>{{$interest->trans('name')}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('interests'))
                                        <div class="invalid-feedback">
                                            @foreach($errors->get('interests') as $error)
                                            <span>{{$error}}</span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="bio">{{__('global.bio')}}</label>
                                        <textarea rows="5" class="mb-sm-0 form-control  @if($errors->has('bio')) is-invalid @endif" name="bio" placeholder="{{__('global.bio')}}" id="bio">{{inp_value($user, 'bio')}}</textarea>
                                        @error('bio')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </div>
                                    <!-- Social Links -->
                                    <div class="mb-3 col-sm-6">
                                        <label for="twitter">{{__('global.twitter')}}</label>
                                        <input class="mb-sm-0 form-control @error('social_links.twitter') is-invalid @enderror" type="text" name="social_links[twitter]" placeholder="{{__('global.twitter')}}"  value="{{isset($user->social_links['twitter'])? $user->social_links['twitter'] : ''}}"  id="twitter"/>
                                        @error('social_links.twitter')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="facebook">{{__('global.facebook')}}</label>
                                        <input class="mb-sm-0 form-control @error('social_links.facebook') is-invalid @enderror" type="text" name="social_links[facebook]" placeholder="{{__('global.facebook')}}"  value="{{isset($user->social_links['facebook'])? $user->social_links['facebook'] : ''}}"  id="facebook"/>
                                        @error('social_links.facebook')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="linkedin">{{__('global.linkedin')}}</label>
                                        <input class="mb-sm-0 form-control @error('social_links.linkedin') is-invalid @enderror" type="text" name="social_links[linkedin]" placeholder="{{__('global.linkedin')}}"  value="{{isset($user->social_links['linkedin'])? $user->social_links['linkedin'] : ''}}"  id="linkedin"/>
                                        @error('social_links.linkedin')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="youtube">{{__('global.youtube')}}</label>
                                        <input class="mb-sm-0 form-control @error('social_links.youtube') is-invalid @enderror" type="text" name="social_links[youtube]" placeholder="{{__('global.youtube')}}"  value="{{isset($user->social_links['youtube'])? $user->social_links['youtube'] : ''}}" id="youtube" />
                                        @error('social_links.youtube')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="instagram">{{__('global.instgram')}}</label>
                                        <input class="mb-sm-0 form-control  @error('social_links.instagram') is-invalid @enderror" type="text" name="social_links[instagram]" placeholder="{{__('global.instgram')}}"  value="{{isset($user->social_links['instagram'])? $user->social_links['instagram'] : ''}}" id="instagram" />
                                        @error('social_links.instagram')
                                        <span class="invalid-feedback" role="alert">
                                            {{$message}}
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="whatsapp">{{__('global.whatsapp')}}</label>
                                        <input class="mb-sm-0 form-control  @error('social_links.whatsapp') is-invalid @enderror" type="text" name="social_links[whatsapp]" placeholder="{{__('global.whatsapp')}}"  value="{{isset($user->social_links['whatsapp'])? $user->social_links['whatsapp'] : ''}}" id="whatsapp" />
                                        <div class="invalid-feedback">
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3 mt-2">
                                        <label for="image">{{__('global.image')}}</label>
                                        <label for="image-input" id="image-input-label" class="cu_input mt-2 d-flex align-items-center px-3 py-2 flex-wrap">
                                            <button class="border border-dark rounded-sm mr-2 bg-light"
                                                style="pointer-events: none">{{ __('global.upload') }}
                                                {{ __('global.image') }}</button>
                                        </label>
                                        <input type="file" name="image" id="image-input" accept="image/*" class="form-control cu_input mt-2 @if($errors->has('image')) is-invalid @endif" hidden>
                                        <input type="hidden" name="image_cropping">
                                        <div id="crop-preview" class="crop-preview preview p-0 m-auto" data-target="#profile-pic-chooser" onclick="$('#profile-pic-chooser').modal('show')">
                                            <div class="profile-preview-div preview-div">
                                                <a href="#" data-toggle="modal" data-target="#profile-pic-chooser">
                                                    <img class="profile-pic-image" height="100" width="100" src="{{storage_asset($user->profile_pic)}}" alt="user profile image">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="revertToDefaultImage" name="revertImage">
                                            <label class="custom-control-label text-secondary"
                                                for="revertToDefaultImage"><strong>{{ __('global.revert_default_img') }}</strong></label>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mx-auto d-block">{{__('global.submit')}}</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h2 class="h5">{{ __('global.change_password') }}</h2>
                            </div>
                        </div>
                        @if(session()->has('password_changed'))
                        <label class="w-100 alert alert-success alert-dismissible fade show" role="alert">
                            {{session()->get('password_changed')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </label>
                        @endif
                        <div class="panel-body">
                            <form class="mt-3" action="{{route('web.dashboard.account.password.change')}}" method="post">
                                @csrf
                                @method('put')
                                <div id="password-change">
                                    <div class="row w-100 mx-auto">
                                        <div class="mb-3 col-12">
                                            <label
                                                for="current_password">{{ __('global.current_password') }}*</label>
                                            <div class="input-group">
                                                <input
                                                    class="mb-sm-0 form-control @error('current_password') is-invalid @enderror"
                                                    type="password" name="current_password"
                                                    placeholder="{{ __('global.current_password') }}" required
                                                    id="current_password" />
                                                <div class="input-group-append">
                                                    <button type="button" class="input-group-text show-password"><i class="fa-solid fa-eye-slash" aria-hidden="true"></i></button>
                                                </div>
                                                @if($errors->has('current_password'))
                                                    <div class="invalid-feedback">
                                                        @foreach($errors->get('current_password') as $error)
                                                            <span>{{$error}}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3 col-12">
                                            <label
                                                for="new_password">{{ __('global.new_password') }}*</label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="mb-sm-0 form-control  @error('password') is-invalid @enderror"
                                                    name="password"
                                                    placeholder="{{ __('global.new_password') }}"
                                                    required id="password" />
                                                <div class="input-group-append">
                                                    <button type="button" class="input-group-text show-password"><i class="fa-solid fa-eye-slash" aria-hidden="true"></i></button>
                                                </div>
                                                @if($errors->has('password'))
                                                    <div class="invalid-feedback">
                                                        @foreach($errors->get('password') as $error)
                                                            <span>{{$error}}</span><br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label
                                                for="password_confirmation">{{ __('global.confirm_new_password') }}*</label>
                                            <div class="input-group">
                                                <input
                                                    class="mb-sm-0 form-control @error('password_confirmation') is-invalid @enderror"
                                                    type="password" name="password_confirmation"
                                                    placeholder="{{ __('global.confirm_new_password') }}" required
                                                    id="password_confirmation" />
                                                <div class="input-group-append">
                                                    <button type="button" class="input-group-text show-password"><i class="fa-solid fa-eye-slash" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mx-auto d-block">{{__('global.change_password')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cropper modal -->
        <div class="modal fade" id="profile-pic-chooser" data-value="profile_image_copper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('global.crop_image') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100" style="height: 400px">
                            <img class="profile-pic-chooser-image mx-100 mh-100 d-block" alt="user image chooser" src="{{storage_asset($user->profile_pic)}}">
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            <button onclick="handleZoomIn()" class="btn btn-secondary @if(locale() == 'ar') ml-3 @else mr-3  @endif">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </button>
                            <button onclick="handleZoomOut()" class="btn btn-secondary">
                                <i class="fa-solid fa-magnifying-glass-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary crop-image-btn" data-dismiss="modal">{{__('global.done_cropping')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js" integrity="sha512-ooSWpxJsiXe6t4+PPjCgYmVfr1NS5QXJACcR/FPpsdm6kqG1FmQ2SVyg2RXeVuCRBLr0lWHnWJP6Zs1Efvxzww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<script>
    let cropper;
    let modalShowed = false;
    const profile_image_copper = {
        cropper: null,
        autoCropArea: 0.8,
        cropping_data: {},
        input: 'image_cropping',
        preview: '.profile-preview-div'
    };

    $(document).ready(function(){
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(".profile-pic-chooser-image").attr("src", e.target.result);
                    $("#profile-pic-chooser").modal("show")
                };
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }
        $("#image-input").on("change", function(e) {
            let preview_div = $(this).prev($('.crop-preview')).first();
            let data_target = preview_div.attr('data-target');
            readURL(this);
            preview_div.attr('onclick', "$('" + data_target + "').modal('show')");
            if (e.target.files[0]?.name) {
                document.querySelector("#image-input-label span").innerHTML = e.target.files[0]?.name;
            }
        })

        $("#profile-pic-chooser").on('shown.bs.modal', function() {
            modalShowed = true;
            const img = document.querySelector(".profile-pic-chooser-image")
            if (cropper) {
                cropper.replace(img.src)
                return;
            }
            cropper = new Cropper(img, {
                autoCropArea: profile_image_copper.autoCropArea,
                data: profile_image_copper.cropping_data,
                aspectRatio: 1 / 1,
                minCropBoxWidth: 200,
                cropBoxResizable: false,
                crop(event) {
                    if (modalShowed) {
                        profile_image_copper.cropping_data = event.detail;
                        $('input:hidden[name=image_cropping]').val(cropper.getCroppedCanvas().toDataURL())
                    }
                }
            })
        })

        $("#profile-pic-chooser").on('hidden.bs.modal', function() {
            modalShowed = false;
        })

        function getRoundedCanvas(sourceCanvas) {
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');
            var width = sourceCanvas.width;
            var height = sourceCanvas.height;

            canvas.width = width;
            canvas.height = height;
            context.imageSmoothingEnabled = true;
            context.drawImage(sourceCanvas, 0, 0, width, height);
            context.globalCompositeOperation = 'destination-in';
            context.beginPath();
            context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
            context.fill();
            return canvas;
        }


        $(".crop-image-btn").on("click", function() {
            if (cropper) {
                let url;
                getRoundedCanvas(cropper.getCroppedCanvas()).toBlob(blob => {
                    url = URL.createObjectURL(blob)
                    $(`${profile_image_copper.preview} img`).attr("src", url)
                });
            }
        });
        document.getElementById("revertToDefaultImage").onchange = function() {
            const status = event.target.checked;
            const image = document.getElementById('image-input');
            const preview = document.getElementById('crop-preview');
            if (status) {
                image.disabled = true;
                preview.classList.add('disabled-overlay')
            } else {
                image.disabled = false;
                preview.classList.remove('disabled-overlay')
            }
        };
    })
    // Zoom in image in crop box
    const handleZoomIn = () => {
        cropper.zoom(0.1)
    }

    // Zoom out image in crop box
    const handleZoomOut = () => {
        cropper.zoom(-0.1)
    }

    $(".chosen-select").chosen({
        no_results_text: "Oops, nothing found!",
        width: "100%"
    })

    // Toggle password
    const togglePasswordBtns = document.querySelectorAll(".show-password");
    Array.from(togglePasswordBtns).forEach((btn, i) => {
        const inputPassword = btn.parentElement.previousElementSibling;
        btn.addEventListener("click", () => {
            if(inputPassword.type === "password") inputPassword.type = "text";
            else inputPassword.type = "password"
        })
    })

    // social links validation
    $('#twitter').focusout(function(){
        let twit = $(this).val();
        if(twit.length > 0 && ! twit.includes("twitter.com")){
            $(this).addClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("{{__('global.invalid_twitter_link')}}");
        } else {
            $(this).removeClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("");
        }
    });

    $('#facebook').focusout(function(){
        let fb = $(this).val();
        if(fb.length > 0 && ! fb.includes("facebook.com")){
            $(this).addClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("{{__('global.invalid_facebook_link')}}");
        } else {
            $(this).removeClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("");
        }
    });

    $('#linkedin').focusout(function(){
        let li = $(this).val();
        if(li.length > 0 && ! li.includes("linkedin.com")){
            $(this).addClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("{{__('global.invalid_linkedin_link')}}");
        } else {
            $(this).removeClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("");
        }
    });

    $('#youtube').focusout(function(){
        let yt = $(this).val();
        if(yt.length > 0 && ! yt.includes("youtube.com")){
            $(this).addClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("{{__('global.invalid_youtube_link')}}");
        } else {
            $(this).removeClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("");
        }
    });

    $('#instagram').focusout(function(){
        let insta = $(this).val();
        if(insta.length > 0 && ! insta.includes("instagram.com")){
            $(this).addClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("{{__('global.invalid_instgram_link')}}");
        } else {
            $(this).removeClass('is-invalid');
            $(this).next($('.invalid-feedback span')).html("");
        }
    });

    $('#password_confirmation').focusout(function(){
        let pass_confirm = $(this).val();
        let pass = $('#password').val();
        if(pass != pass_confirm){
            $(this).addClass('is-invalid');
            $(this).add($('.invalid-feedback span')).html("{{__('global.passwords_dont_match')}}");
        } else {
            $(this).removeClass('is-invalid');
            $(this).add($('.invalid-feedback span')).html("");
        }
    });
</script>
@endsection
@endsection
