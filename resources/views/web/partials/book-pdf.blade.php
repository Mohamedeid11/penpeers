@php
    $book_participants = $book->participants()->pluck('user_id')->toArray();
@endphp
@if(auth()->check() && in_array(Auth::id(),  $book_participants))
@include("web.partials.book-pdf-html")
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/super-build/ckeditor.js"></script>
<script>
    const language = {!! json_encode($book->language) !!};
    const book = {!! json_encode($book) !!};

    CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
        toolbar: {
            items: ['exportPDF'],
            shouldNotGroupWhenFull: true
        },
        removePlugins: [
            'CKBox',
            'CKFinder',
            'EasyImage',
            'RealTimeCollaborativeComments',
            'RealTimeCollaborativeTrackChanges',
            'RealTimeCollaborativeRevisionHistory',
            'PresenceList',
            'Comments',
            'TrackChanges',
            'TrackChangesData',
            'RevisionHistory',
            'Pagination',
            'WProofreader',
            'MathType'
        ],
        fontFamily: {
            options: [
                'PT Serif, sans-serif'
            ],
            supportAllValues: true
        },
        language: {
            ui: "en",
            content: language
        },
        exportPdf: {
            fileName: `${book.title}.pdf`,
            stylesheets: ["{{asset('css/web/pdf.css')}}", "EDITOR_STYLES", "{{asset('css/web/pdf2.css')}}"],
            converterOptions: {
                format: "A4",
                margin_top: "20mm",
                margin_bottom: "30mm",
                margin_right: "20mm",
                margin_left: "20mm",
                footer_html: `
                    <div style="text-align: center; font-size: 10px; margin-bottom: 10mm">
                        <p> -<span class="pageNumber"></span>- </p>
                        <p> PenPeers </p>
                    </div>`,
            },
        },
    })
    .then( editor => {
        window.editor = editor;
        editor.enableReadOnlyMode("editor");
    })
    .catch( error => console.error( error ));
</script>
@endif
