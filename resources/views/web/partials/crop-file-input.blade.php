<div class="form-group">
    <label for="exampleInputEmail13">{{$display_name}}</label>
    <div class="crop-preview preview mx-auto my-1"
        data-value="{{$name}}"
        data-target="#crop-modal-{{$name}}">
        <div id="{{$name}}-preview" class="position-relative">
            <img height="100"
                width="100"
                style="object-fit: contain"
                src="{{ old($name) ? old($name) :
                    (isset($book) ? asset('storage/'.$book->$name) :
                    (isset($post)? asset('storage/'.$post->image) :
                    asset('storage/'.$default_img))) }}">
            <input 
            type="checkbox" 
            class="custom-control-input" 
            id="{{ $name }}_revertToDefaultImage"
            name="{{ $name }}_revertImage" 
            hidden 
            data-default="{{asset('storage/'.$default_img)}}">
            <label class="position-absolute revert-image {{ $name }}_revert @if(!isset($book) && !isset($post)) d-none @endif"
                for="{{ $name }}_revertToDefaultImage">x</label>
        </div>
    </div>
    <input
        class="form-control crop-file-input @if($errors->has($name)) is-invalid @endif"
        type="file"
        name="{{$name}}"
        accept="image/*"
        id="{{$name}}-input"
        hidden>
    <label for="{{$name}}-input" class="d-flex align-items-center form-control flex-wrap">
        <button class="border border-dark rounded-sm bg-light @if(locale() == 'ar') ml-2 @else mr-2 @endif"
            style="pointer-events: none">{{ __('global.upload') }}
            {{ __('global.image') }}</button>
        <span>{{__('admin.placeholder_text', ['name'=>__('global.image')])}}</span>
    </label>
    <input type="hidden" name="{{$name}}_crop">
    @if($errors->has($name))
        <div class="invalid-feedback">
            @foreach($errors->get($name) as $error)
                {{$error}}<br>
            @endforeach
        </div>
    @endif
</div>
