<div class="modal fade" id="share-to-email-modal" tabindex="-1" aria-labelledby="share-to-email-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title h5" id="share-to-email-label">
                    {{ __('global.share_page_to_email') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" method="POST" action="{{route('web.shareLinkToMail')}}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="link" name="link" value="{{Request::url()}}">
                    @if (Auth::guest())
                    <div class="form-group">
                        <label for="sender-input-name" style="color: white !important;">
                            {{ __('global.your_name') }} <sup class="color-danger">*</sup></label>
                        <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="sender-name-input" name="sender_name"
                               placeholder="{{__('global.enter_name')}}" data-validation="required" required>
                        @if($errors->has('sender_name'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('sender_name') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="title-input"
                            style="color: white !important;">{{ __('global.your_email') }} <sup
                                class="color-danger">*</sup></label>
                        <input type="email" class="form-control @if($errors->has('sender_email')) is-invalid @endif" id="sender-input-email" name="sender_email"
                               placeholder="{{__('global.enter_email')}}" data-validation="required" required>
                        @if($errors->has('receiver_email'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('receiver_email') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="receiver-input-name" style="color: white !important;">{{__('global.receiver_name')}} <sup class="color-danger">*</sup></label>
                        <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="receiver-name-input" name="receiver_name"
                               placeholder="{{__('global.enter_receiver_name')}}" data-validation="required" required>
                        @if($errors->has('receiver_name'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('receiver_name') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="title-input" style="color: white !important;">{{__('global.receiver_email')}} <sup class="color-danger">*</sup></label>
                        <input type="email" class="form-control @if($errors->has('receiver_email')) is-invalid @endif" id="receiver-input-email" name="receiver_email"
                               placeholder="{{__('global.enter_receiver_email')}}" data-validation="required" required>
                        @if($errors->has('receiver_email'))
                            <div class="invalid-feedback">
                                @foreach($errors->get('receiver_email') as $error)
                                    {{$error}}<br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! htmlFormSnippet() !!}
                    </div>

                    <div class="mt-2 form-group">
                        <span class="recaptcha-error d-none alert alert-danger">Recaptcha is required</span>
                    </div>

                    @if($errors->has('g-recaptcha-response'))
                        @foreach($errors->get('g-recaptcha-response') as $error)
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <span id="error-captcha" class="alert alert-danger">
                                        {{($error)}}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary">{{__('global.send')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
