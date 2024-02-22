<div class="modal fade crop-modal" id="crop-modal-{{$name}}" data-value="{{$name}}_image_cropper">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title ml-auto">{{__('global.crop_image')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="width: 100%; height: 400px">
                    <img class="pic-chooser-image" id="{{$name}}-pic-chooser-image" src="" style="max-width: 100%;display: block;max-height:100%">
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    <button onclick="handleZoomIn(this)"
                        class="btn btn-secondary @if(locale() == 'ar') ml-3 @else mr-3 @endif" data-name="{{ $name }}">
                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                    </button>
                    <button onclick="handleZoomOut(this)" class="btn btn-secondary" data-name="{{$name}}">
                        <i class="fa-solid fa-magnifying-glass-minus"></i>
                    </button>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn btn-dark crop-image-btn" onclick="doneCropping('{{$name}}')" data-dismiss="modal" >{{__('global.done_cropping')}}</button>
            </div>
        </div>
    </div>
</div>
