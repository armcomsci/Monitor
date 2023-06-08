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
                            <li class="breadcrumb-item active" aria-current="page"><span>ยืนยันตำแหน่งร้านค้า</span></li>
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
                                <form id="FindCust" action="{{ url('/AdminConfirmImg') }}" method="post">
                                    @csrf
                                    <div class="form-group row mt-4">
                                        <div class="col-3">
                                            <input type="text" class="form-control" name="CustName" placeholder="ชื่อร้าน" value="{{ $Custname }}">
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control " name="status" >
                                                @php
                                                    if($status == 'N'){
                                                        $selected =  "selected";
                                                        $selected2 =  "";
                                                        $selected3 =  "";
                                                    }elseif($status == 'Y'){
                                                        $selected =  "";
                                                        $selected2 =  "selected";
                                                        $selected3 =  "";
                                                    }elseif($status == 'A'){
                                                        $selected =  "";
                                                        $selected2 =  "";
                                                        $selected3 =  "selected";
                                                    }
                                                @endphp
                                                <option value="N" {{ $selected }} >ยังไม่ยืนยัน</option>
                                                <option value="Y" {{ $selected2 }} >ยืนยันแล้ว</option>
                                                <option value="A" {{ $selected3 }} >ทั้งหมด</option>
                                            </select>
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="submit" class="btn btn-outline-primary" id="Find"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div id="dataImgCust">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive" style="height: 650px;">
                                                <table class="table table-bordered mb-4" id="Table-JobClose" >
                                                    <thead style="background: #2e60e9">
                                                        <tr>
                                                            <th>ร้านค้า</th>
                                                            <th>ที่อยู่</th>
                                                            <th>รูปภาพ</th>
                                                            <th>ผู้อนุมัติคนแรก</th>
                                                            <th>แผนที่</th>
                                                            <th>ยืนยัน/ปฏิเศษ</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($imgCust) != "0")
                                                            @foreach ($imgCust as $data)
                                                                <tr>
                                                                    <td>{{ $data->CustName  }}</td>
                                                                    <td>{{ $data->ShiptoAddr1  }}</td>
                                                                    <td>
                                                                        <img src="https://xm.jtpackconnect.com/transport/public/{{ $data->ImgPath  }}" alt="" style="width: 250px;height:250px;" class="ShowImg">
                                                                    </td>
                                                                    <td>{{ $data->AppvName  }}</td>
                                                                    <td>
                                                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $data->Latitude.",".$data->Longitude }}" target="_blank">
                                                                            <img src="{{ asset('icon/location.png') }}" alt="">
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <i class="fa-solid fa-check fa-2xl appvImg2"  style="color: #06e136;" data-status="Y" data-custid="{{ $data->CustID }}" data-listno="{{ $data->ShipListNO }}"></i>
                                                                        <i class="fa-solid fa-x fa-2xl ml-3 appvImg2"  style="color: #ef2a4b;" data-status="N" data-custid="{{ $data->CustID }}" data-listno="{{ $data->ShipListNO }}"></i>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else 
                                                        <tr>
                                                            <td colspan="6"class="text-center"><h4>ไม่พบข้อมูล</h4></td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
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
<div class="modal fade " id="ConfirmImgCust" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="">รูปภาพร้าน</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 750px;" id="">
                <img src="" alt="" id="imgPath" style="width: 100%; height: 700px;">
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
    $(document).ready(function () {
        $('.ShowImg').click(function (e) { 
            e.preventDefault();
            let imgPath = $(this).attr('src');
            $('#imgPath').attr('src',imgPath);
            $('#ConfirmImgCust').modal('show');
        });
        $('.appvImg2').click(function(e){
            let status = $(this).data('status');
            let custid = $(this).data('custid');
            let shipno = $(this).data('listno');

            let textConfirm;
            if(status == "Y"){
                textConfirm = "ยืนยัน";
            }else if(status == "N"){
                textConfirm = "ปฏิเสธ";
            }
            swal({
                title: 'ต้องการ'+textConfirm+'ตำแหน่งของร้านค้า',
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
                        url: url+"/AdminConfirmImgCust",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {custid:custid,shipno:shipno,status:status},
                        beforeSend:function(){
                            // $('.loaddingModal').css('display','block');
                            // $('#ConfirmCustImg,#RejectImg').attr('disabled',true);
                            
                        },
                        success: function (response) {
                            // $('#ConfirmCustImg,#RejectImg').attr('disabled',false);
                            if(response == "success"){
                                // $('#ConfirmImgCust').modal('hide');
                                swal({
                                    title: 'บันทึกสำเร็จ',
                                    text: '',
                                    type: 'success',
                                    padding: '2em'
                                })
                            }else{
                                swal({
                                    title: 'Error',
                                    text: response,
                                    type: 'error',
                                    padding: '2em'
                                })
                            }
                        
                        }
                    });
                }
            });
        })
    });
</script>
@endsection