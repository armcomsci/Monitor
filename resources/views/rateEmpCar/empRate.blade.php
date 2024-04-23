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
 <link href="{{ asset('theme/assets/css/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
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
    .table > tbody > tr > td{
        white-space: unset;
    }
    .activeTr{
        background: #e4f852;
    }
    .setHeight{
        height: 700px; 
        overflow: auto; 
        overflow-x: hidden;
    }
    .user-profile > img {
        width: 130px !important;
        height: 130px !important;
        margin: auto !important;
    }
    .mail-box-container .avatar{
        width: 64px;
        height: 64px;
    }
    .hiddenimg {
        display: none;
    }
    .hidden-list:hover ~ .hiddenimg {
        display: block;
        position: absolute;
        z-index: 2;
        left: 100px;
        bottom: -20px;
        background: #fff;
        border-radius: 10px;
        padding: 5px;
    }
    .searchable-container .searchable-items.list .items:not(.items-header-section) .item-content:hover{
        background: #ee790a54;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>ประเมินคนรถ</span></li>
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
                            <div id="mailbox-inbox" class="accordion mailbox-inbox p-3 ">
                                <div class="filtered-list-search layout-spacing align-self-center">
                                    <form class="row" onSubmit="return false" method="post" action="{{ url()->current() }}">
                                        @csrf
                                        <div class="col-xl-2 col-lg-2 col-md-2">
                                            <select class="form-control" name="Month_rate" onchange="this.form.submit()">
                                                @php
                                                    $M_ago  = strtotime("-1 Months");
                                                    $M_ago  = date('m',$M_ago);
    
                                                    $M_current = date('m');

                                                    $selected  = '';
                                                    $selected2 = '';

                                                    if($Month_rate != "" && $Month_rate == $M_ago){
                                                        $selected2 = "selected";
                                                    }else if($Month_rate == $M_current || $Month_rate == ''){
                                                        $selected = "selected";
                                                    }
                                                @endphp
                                                <option value="{{ $M_ago }}" {{ $selected2 }} >{{ getMonth($M_ago)  }}</option>
                                                <option value="{{ $M_current }}"  {{ $selected }} >{{ getMonth($M_current)  }}</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">  
                                            <input type="text" class="form-control product-search" id="input-search" placeholder="ค้นหาด้วย ทะเบียนรถ/รหัส/ชื่อพนักงาน">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search layout-spacing align-self-center">
                                        
                                </div>
                                <div class="layout-spacing layout-top-spacing pl-3" id="cancel-row">
                                    <div class="widget-content searchable-container list">
                                        <h5 style="color:red; float: right;">***หมายเหตุในการประเมินจะเป็นการหักคะแนนเดือนปัจจุบัน***</h5>
                                        <div class="searchable-items list">
                                            <div class="items items-header-section">
                                                <div class="item-content" style="background: darkorange">
                                                    <div style="width: 90px;">
                                                        <h4>รูปภาพ</h4>
                                                    </div>
                                                    <div style="width: 120px;">
                                                        <h4>ทะเบียนรถ</h4>
                                                    </div>
                                                    <div style="width: 200px;">
                                                        <h4>รหัส/ชื่อ-นามสกุล</h4>
                                                    </div>
                                                    <div style="width: 150px;">
                                                        <h4>เบอร์ติดต่อ</h4>
                                                    </div>
                                                    <div  style="width: 100px;">
                                                        <h4>คะแนน</h4>
                                                    </div>
                                                    <div  style="width: 100px;">
                                                        <h4>หัวข้อประเมินล่าสุด</h4>
                                                    </div>
                                                    <div  style="width: 100px;">
                                                        <h4>ผู้ประเมิน</h4>
                                                    </div>
                                                    <div class="">
                                                        <h4>ประเมิน</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="setHeight">
                                                @foreach ($EmpName as $item)
                                                <div class="items" data-id="{{ $item->EmpDriverCode }}">
                                                    <div class="item-content">
                                                        <div style="width: 100px;">
                                                            <div class="avatar" >
                                                                <img alt="avatar" src="https://images.jtpackconnect.com/empdrive/{{ $item->EmpDriverCode.".jpg" }}" class="rounded-circle hidden-list"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"  />
                                                                <span class="hiddenimg">
                                                                    <img  src="https://images.jtpackconnect.com/empdrive/{{ $item->EmpDriverCode.".jpg" }}"  style="width: 250px; height: 250px;"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"/>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        {{-- <div style="width: 150px;" class="user-profile">
                                                            <img src="https://images.jtpackconnect.com/empdrive/{{ $item->EmpDriverCode.".jpg" }}" onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"  alt="avatar">
                                                        </div> --}}
                                                        <div style="width: 100px;">
                                                            <p>{{ $item->VehicleCode }}</p>
                                                        </div>
                                                        <div style="width: 200px;">
                                                            <p>{{ $item->EmpDriverName }}</p>
                                                        </div>
                                                        <div style="width: 150px;">
                                                            <p>{{ $item->EmpDriverTel }}</p>
                                                        </div>
                                                        <div style="width: 100px;">
                                                            <p>{{ 100-$item->SumScoreRate }}</p>
                                                        </div>
                                                        <div style="width: 100px;">
                                                            <p>{{ $item->SubTitleName }}</p>
                                                        </div>
                                                        <div style="width: 100px;">
                                                            <p>{{ $item->RateFullname }}</p>
                                                        </div>
                                                        <div class="action-btn">       
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 edit RateEmp" data-id="{{ $item->EmpDriverCode }}"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalRate" tabindex="-1" role="dialog" aria-labelledby="ModalRate" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ประเมินพนักงาน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 660px;" id="ProfileRate"> 

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
<script src="{{ asset('theme/custom/jquery.mask.js') }}"></script>
<script src="{{ asset('theme/plugins/apex/apexcharts.min.js') }}"></script>
<script>
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var EmpDrivCode;

    $(document).ready(function () {

        $('#input-search').on('keyup', function() {
            var rex = new RegExp($(this).val(), 'i');
            $('.searchable-items .items:not(.items-header-section)').hide();
            $('.searchable-items .items:not(.items-header-section)').filter(function() {
                return rex.test($(this).text());
            }).show();
        });

        $(".RateEmp").click(function (e) { 
            e.preventDefault();
            let empCode = $(this).data('id');
            let Month_rate = $("select[name='Month_rate']").val();
            EmpDrivCode = empCode;
            $.ajax({
                type: "post",
                url: url+"/RateProfileEmpDriv",
                data: {'empCode':empCode,'Month_rate':Month_rate},
                beforeSend:function(){
                    $('#ProfileRate').empty();
                },
                success: function (response) {
                    $('#ProfileRate').html(response);
                }
            });
            $('#ModalRate').modal('show');
        });
    });

    $(document).on('change','#RateTitle', function (e) {  
        e.preventDefault();
        let val = $(this).val();
        $.ajax({
            type: "post",
            url: url+"/RateGetSubTitle",
            data: {'val':val},
            beforeSend:function(){
                $('#RateSubTitle').empty();
                $('#RateSubTitle').attr('readonly',true);
            },
            success: function (response) {
                $('#RateSubTitle').attr('readonly',false);

                let html;
                $.each(response, function (index, value) { 
                    html += "<option value=\""+value.id+"\" >"+value.Title+"</option>";
                });
                $('#RateSubTitle').append(html);
            }
        });
    });
    
    $(document).on('submit','#SaveRateEmpDriv', function (e) {  
        e.preventDefault();
        let required        = $('.required');
        let required_status = true;

        $.each(required, function(key,val) {             
            let input = $(this);
            if(input.val() == ""){
                let textAlert = input.prev().text();
                swal({
                    title: 'กรุณาระบุข้อมูล',
                    text: 'ระบุ'+textAlert,
                    type: 'warning',
                    padding: '2em'
                }).then((result) => {
                    input.focus();
                });
                required_status = false;
                return false;
            }    
        }); 

        if(required_status){
            let FormSave = new FormData($('#SaveRateEmpDriv')[0]);
            let Month_rate = $("select[name='Month_rate']").val();
            FormSave.append('EmpCode', EmpDrivCode);
            FormSave.append('Month_rate',Month_rate);
            $.ajax({
                type: "post",
                url: url+"/SaveRateEmpDriv",
                data: FormSave,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    swal({
                        title: 'loadding....',
                        text: '',
                        timer: 2000,
                        button: false,
                        closeModal: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false
                        // timerProgressBar: true
                    })
                },
                success: function (response) {
                    swal.close();
                    if(response == 'success'){
                        swal({
                            title: 'บันทึกสำเร็จ',
                            text: '',
                            type: 'success',
                            padding: '2em'
                        }).then((result) => {
                           location.reload();
                        });
                    }else{
                        swal({
                            title: 'Error',
                            text: response,
                            type: 'error',
                            padding: '2em'
                        })
                    }
                }
            })
        }
    });
</script>
@endsection