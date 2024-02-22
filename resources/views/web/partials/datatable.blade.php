<!-- Required datatable js-->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{asset('plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables/jszip.min.js')}}"></script>
<script src="{{asset('plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

<script>
    let datatable = $('.datatable').DataTable({
        dom: "Bflrtip",
        buttons: [
            {
                extend: "copy",
                className: "btn-primary",
                text: "{{__('global.copy')}}"
            }, 
            {
                extend: "csv",
                className: "btn-primary"
            },
            {
                extend: "excel",
                className: "btn-primary"
            }, 
            {
                extend: "pdf",
                className: "btn-primary"
            },
            {
                extend: "print",
                className: "btn-primary",
                text: "{{ __('global.print') }}"
            }
        ],
        responsive: true,
        "order": [],
        // "order": false,
        columnDefs: [{
            orderable: false,
            targets: [-1]
        }],
        language: lang === "ar" ? {
            sProcessing: "جارٍ التحميل...",
            sLengthMenu: "أظهر _MENU_ مدخلات",
            sZeroRecords: "لم يعثر على أية سجلات",
            sZeroRecords: "لم يعثر على أية سجلات",
            sInfo: "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            sInfoEmpty: "يعرض 0 إلى 0 من أصل 0 سجل",
            sInfoFiltered: "(منتقاة من مجموع _MAX_ مُدخل)",
            sInfoPostFix: "",
            sSearch: "ابحث:",
            sUrl: "",
            oPaginate: {
                sFirst: "الأول",
                sPrevious: "السابق",
                sNext: "التالي",
                sLast: "الأخير"
            }
        } : {}
    });
</script>