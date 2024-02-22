    <script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
    <script>
        DecoupledDocumentEditor
            .create( document.querySelector( '.editor' ), {
                toolbar: {
                    items: [
                        'htmlEmbed',
                        '|',
                        'heading',
                        '|',
                        'fontSize',
                        'fontFamily',
                        '|',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        'strikethrough',
                        '|',
                        'alignment',
                        '|',
                        'numberedList',
                        'bulletedList',
                        '|',
                        'outdent',
                        'indent',
                        '|',
                        'todoList',
                        'link',
                        'blockQuote',
                        'imageUpload',
                        'insertTable',
                        '|',
                        'undo',
                        'redo',
                        '|',
                        'pageBreak',

                    ]
                },
                language: 'en',
                licenseKey: '',
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side'
                    ]
                },
                autosave: {
                    save(editor) {
                        return changeData(editor.getData());
                    }
                }
            } )
            .then( editor => {
                window.editor = editor;
                document.querySelector( '.document-editor__toolbar' ).appendChild( editor.ui.view.toolbar.element );
                document.querySelector( '.ck-toolbar' ).classList.add( 'ck-reset_all' );
            } )
            .catch( error => {
                console.error( 'Oops, something went wrong!' );
                console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                console.warn( 'Build id: mhwb3tt88jat-u9490jx48w7r' );
                console.error( error );
            } );
        function changeData(data){
            $('.ckeditor-input').val(data);
        }
    </script>

