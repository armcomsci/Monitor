@extends('layout.template')

@section('css')
 <!--  BEGIN CUSTOM STYLE FILE  -->
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/editors/quill/quill.snow.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/assets/css/forms/theme-checkbox-radio.css') }}">
 <link href="{{ asset('theme/assets/css/apps/mailbox.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('theme/assets/css/timeline.css') }}" rel="stylesheet" type="text/css">

 <script src="{{ asset('theme/plugins/sweetalerts/promise-polyfill.js') }}"></script>
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/assets/css/elements/alert.css') }}">
 <link href="{{ asset('theme/plugins/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('theme/plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('theme/plugins/notification/snackbar/snackbar.min.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('theme/assets/css/elements/avatar.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('theme/assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/select2/select2.min.css') }}">
 <link rel="stylesheet" href="{{ asset('theme/assets/css/daterangepicker.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/table/datatable/datatables.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/table/datatable/custom_dt_html5.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/table/datatable/dt-global_style.css') }}">
 <style>
    thead{
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .select2-container--default .select2-selection--multiple{
        padding: 4px 13px;
    }
    .select2-container--default .select2-selection--multiple{
        background: #ffffff;
    }
    tr{
        cursor: pointer;
    }
    .mail-box-container .rounded-circle{
        width: 84px;
        height: 84px;
    }
    .table > tbody > tr > td{
        white-space: unset;
    }
    .activeTr{
        background: #e4f852;
    }
 </style>
@endsection

@section('sub-header')
<div class="sub-header-container">
    <header class="header navbar navbar-expand-sm">
        <a href="{{ url('/') }}" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

        <ul class="navbar-nav flex-row">
            <li>
                <div class="page-header">
                    <nav class="breadcrumb-one" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">หน้าหลัก</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><span>รายงานสถิติการลาคนรถ</span></li>
                        </ol>
                    </nav>
                </div>
            </li>
        </ul>
    </header>
</div>   
@endsection

@section('content')
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-xl-12  col-md-12">
                        <div class="mail-box-container">
                            <div id="mailbox-inbox" class="accordion mailbox-inbox p-3">
                                <form id="FindWorkLeave" onSubmit="return false">
                                    <div class="form-group row mt-4">
                                        @php
                                            $CurentM = date('m');

                                            $M = Array("01" => "ม.ค.",
                                                       "02" => "ก.พ.",
                                                       "03" => "มี.ค.",
                                                       "04" => "เม.ย.",
                                                       "05" => "พ.ค.",
                                                       "06" => "มิ.ย.",
                                                       "07" => "ก.ค.",
                                                       "08" => "ส.ค.",
                                                       "09" => "ก.ย.",
                                                       "10" => "ต.ค.",
                                                       "11" => "พ.ย.",
                                                       "12" =>"ธ.ค.");
                                        @endphp 
                                        <div class="col-2">
                                            <select class="form-control" name="Month" >
                                                <option value=""></option>
                                                    @foreach ($M as $key => $itemM)
                                                        @php
                                                            $selectedM = '';
                                                            if($key == $CurentM ){
                                                                $selectedM = "selected";
                                                            }
                                                        @endphp
                                                        <option value="{{ $key }}" {{ $selectedM }} >{{ $itemM }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control " name="Year" >
                                                <option value="">---เลือกปี----</option>
                                                @php
                                                    $YearStart = 2024;
                                                    $Year = date('Y');
                                                @endphp
                                                @for ($i = $YearStart ; $i < $YearStart+10; $i++)
                                                    @php
                                                        $selectedYear = '';
                                                        if($Year == $i ){
                                                            $selectedYear = "selected";
                                                        }
                                                    @endphp
                                                    <option value="{{ $i }}" {{ $selectedYear }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control" name="CarTypeCode" >
                                                <option value="">-- ทั้งหมด --</option>
                                                <option value="CT001">รถเล็ก</option>
                                                <option value="CT002">รถกลาง</option>
                                                <option value="CT003">รถใหญ่</option>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control" id="groupCode" name="groupCode"  >
                                                <option value="A"  >พนักงานในบริษัท</option>
                                                <option value="EG-0003" >พนักงานนอกบริษัท</option>
                                            </select>
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="button" class="btn btn-outline-primary" id="Find"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>

                                <div class="loaddingModal" style="height: 500px;"></div>
                                <div id="DataWorkLeave">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalDetailEmpWork" tabindex="-1" role="dialog" aria-labelledby="ModalDetailEmpWork" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title">รายละเอียดการลา</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 500px;" id="ProfileWork"> 

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('theme/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('theme/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('theme/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('theme/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>    
<script src="{{ asset('theme/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('theme/plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
<script>
    var EmpDriveCode;
    $('#Find').click(function (e) { 
        $.ajax({
            type: "post",
            url: url+"/FindLeaveWork",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $('#FindWorkLeave').serialize(),
            beforeSend:function(){
                $('.loaddingModal').css('display','block');
                $('#DataWorkLeave').empty();
            },
            success: function (response) {
                $('.loaddingModal').css('display','none');
                $('#DataWorkLeave').html(response); 
                $('#TableWorkLeave').dataTable({
                    "dom": "<'dt--top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                        buttons: {
                            buttons: [
                                
                            //     { extend: 'excel', className: 'btn btn-sm' },

                            ]
                        },
                    "oLanguage": {
                        "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                        "sInfo": "Showing page _PAGE_ of _PAGES_",
                        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                        "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                    },
                    "stripeClasses": [],
                    "lengthMenu": [7, 10, 20, 50],
                    "pageLength": 7 ,
                    "ordering": true
                });
            }
        });
    });

    $(document).on('click','.DetailWorkLeave',function(e){
        let empcode         = $(this).data('empcode');
        let Month           = $("select[name='Month']").val();
        let Year            = $("select[name='Year']").val();
        EmpDriveCode        = empcode;
        $.ajax({
            type: "post",
            url: url+"/DetailEmpDrivWork",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'empCode':empcode,'Month':Month,'Year':Year},
            beforeSend:function(){
                $('#ProfileWork').empty();
            },
            success: function (response) {
                $('#ModalDetailEmpWork').modal('show');
                $('#ProfileWork').html(response);
            }
        });

    });

    $(document).on('click','.deleteWorkEmp',function(e){
        let workid  = $(this).data('workid');
        let tagTr   = $(this).parent().parent();
        swal({
            title: 'ต้องการลบ ?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            padding: '2em'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: url+"/ClearWorkEmp",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {workid:workid},
                    // dataType: "dataType",
                    success: function (response) {
                        if(response == "success"){
                            // alert(empcode);
                            // console.log($(".DetailRate[data-empcode='" + EmpDriveCode + "']"));
                            $(".DetailWorkLeave[data-empcode='"+EmpDriveCode+"']").click();
                            $('#Find').click();
                        }else{
                            swal({
                                title: 'เกิดข้อผิดพลาด',
                                text: response,
                                type: 'error' 
                            })
                        }
                    }
                });
            }
        });
    });
    
</script>
@endsection