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
                            <li class="breadcrumb-item active" aria-current="page"><span>อนุมัติรายการแก้ไขข้อมูลรถ</span></li>
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
                                <div class="table-responsive" style="height: 650px;">
                                    <table class="table table-bordered mb-4" >
                                        <thead style="background: #f89c33">
                                            <tr>
                                               <th>ลำดับที่</th>
                                               <th>ทะเบียนรถ</th>
                                               <th>รายการแก้ไข</th>
                                               <th>ผู้ขออนุมัติ</th>
                                               <th>เมื่อเวลา</th>
                                               <th>สถานะอนุมัติ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($LogEdit) == 0)
                                                <tr>
                                                    <td colspan="6" class="text-center"><h5>ไม่พบข้อมูล</h5></td>
                                                </tr>
                                            @else
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($LogEdit as $item)
                                                    <tr class="showEdit">
                                                        <td>{{ $no }}</td>
                                                        <td>{{ $item->vehicleCode }}</td>
                                                        <td>
                                                            @php
                                                                $data = json_decode($item->data_update,true);
                                                                // dd($data);
                                                            @endphp
                                                            @foreach ($data as $item2)
                                                                {{ $item2['text']." : ".$item2['val'] }} <br>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            {{ $item->Fullname }}
                                                        </td>
                                                        <td>
                                                            {{ ShowDate($item->created_time,"d-m-Y H:i") }}
                                                        </td>
                                                        <td>
                                                            <i class="fa-solid fa-check fa-2xl ApproveEdit"  style="color: #06e136;" data-id="{{ $item->id }}" data-status="Y" ></i>
                                                            <i class="fa-solid fa-x fa-2xl ml-3 ApproveEdit"  style="color: #ef2a4b;" data-id="{{ $item->id }}" data-status="N" ></i>
                                                        </td>
                                                    </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                                @endforeach
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
        $('.ApproveEdit').click(function(e){
            let status = $(this).data('status');
            let id     = $(this).data('id');
            let txtAlert = $(this).parent().prev().prev().prev().html();
            // console.log(txtAlert);

            let textConfirm;
            if(status == "Y"){
                textConfirm = "อนุมัติ";
            }else if(status == "N"){
                textConfirm = "ปฏิเสธ";
            }

            swal({
                title: 'ต้องการ'+textConfirm+'รายการแก้ไข ?',
                html: txtAlert,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {  
                    $.ajax({
                        type: "post",
                        url: url+"/AdminApporveEditCar",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {id:id,status:status},
                        beforeSend:function(){
                          
                        },
                        success: function (response) {
                            
                            if(response == "success"){
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
                    });
                }
            });
        })
        $('.showEdit').click(function (e) { 
            e.preventDefault();
            // let vehicleCode = $(this).children().next().next().text();
            // let txtAlert    = $(this).children().next().next().next().html();
            // console.log(vehicleCode);
            // swal({
            //     title: 'แก้ไขข้อมูลของทะเบียนรถ : '+vehicleCode,
            //     html: txtAlert,
            //     type: 'warning',
            //     showCancelButton: true,
            //     confirmButtonText: 'ยืนยัน',
            //     cancelButtonText: 'ยกเลิก',
            //     padding: '2em'
            // })
        });
    });
</script>
@endsection