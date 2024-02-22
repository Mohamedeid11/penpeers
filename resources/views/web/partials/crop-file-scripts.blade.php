<script src="//cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.22/fabric.min.js"></script>
<script src="<?=asset('js/web/cropper.min.js')?>"></script>
<script>
    let cropper, cropper2, done_cropping = false;
    const back_image_cropper = {
        cropper: null,
        autoCropArea: 0.8,
        cropping_data: {},
        input: 'back_conver_crop',
        preview: '#back_cover-preview'
    };
    const front_image_cropper = {
        cropper: null,
        autoCropArea: 0.8,
        cropping_data: {},
        input: 'front_conver_crop',
        preview: '#front_cover-preview'
    };

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(`#${input.name}-pic-chooser-image`).attr("src", e.target.result);
                $(`#crop-modal-${input.name}`).modal("show")
            };
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    $(".crop-file-input").on("input", function(e) {
        readURL(this);
        if (e.target.files[0]?.name) {
            e.currentTarget.nextElementSibling.querySelector("span").innerHTML = e.target.files[0]?.name;
        }
    })
    $('.crop-modal').on('shown.bs.modal', function (e) {
        const name = e.currentTarget.id.split("-")[2];
        const img = document.querySelector(`#${name}-pic-chooser-image`)
        if(name === "front_cover") {
            if (cropper) {
                cropper.replace(img.src)
                return;
            }
            cropper = new Cropper(img, {
                autoCropArea: front_image_cropper.autoCropArea,
                data: front_image_cropper.cropping_data,
                aspectRatio: 1 / 1.4142,
                minCropBoxWidth: 200,
                cropBoxResizable: false,
                viewMode: 1,
                crop(event) {
                    front_image_cropper.cropping_data = event.detail;
                }
            })
        } else {
            if (cropper2) {
                cropper2.replace(img.src)
                return;
            }
            cropper2 = new Cropper(img, {
                autoCropArea: back_image_cropper.autoCropArea,
                data: back_image_cropper.cropping_data,
                aspectRatio: 1 / 1.4142,
                minCropBoxWidth: 200,
                cropBoxResizable: false,
                viewMode: 1,
                crop(event) {
                    back_image_cropper.cropping_data = event.detail;
                }
            })
        }
    });
    $(".crop-modal").on('hidden.bs.modal', function(e) {
        const id = e.currentTarget.id;
        const name = id.split("-")[2];
        
        if(!done_cropping) {
            document.querySelector(`.crop-file-input[name='${name}']`).value = ""
            document.querySelector(`label[for='${name}-input'] span`).innerHTML =
            "{{ __('admin.placeholder_text', ['name'=>__('global.image')]) }}";
        } else {
            document.querySelector(`.revert-image.${name}_revert`).classList.remove("d-none")
            document.querySelector(`#${name}_revertToDefaultImage`).checked = false;
        }

        done_cropping = false;
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
        context.rect(0, 0, width, height);
        context.fill();
        return canvas;
    }

    // Zoom in image in crop box
    const handleZoomIn = (e) => {
        if(e.dataset.name === "front_cover") 
            cropper.zoom(0.1);
        else
            cropper2.zoom(0.1);
    }
    
    // Zoom out image in crop box
    const handleZoomOut = (e) => {
        if(e.dataset.name === "front_cover")
            cropper.zoom(-0.1)
        else
            cropper2.zoom(-0.1)
    }

    const doneCropping = (name) => {
        let url, croppedCanvas, id;

        done_cropping = true;

        if(name === "front_cover") {
            croppedCanvas = cropper.getCroppedCanvas();
            id = front_image_cropper.preview;
        }
        else {
            croppedCanvas = cropper2.getCroppedCanvas();
            id = back_image_cropper.preview;
        }

        $(`input:hidden[name=${name}_crop]`).val(croppedCanvas.toDataURL())
        getRoundedCanvas(croppedCanvas).toBlob(blob => {
            url = URL.createObjectURL(blob)
            $(`${id} img`).attr("src", url)
        });
    }
</script>
