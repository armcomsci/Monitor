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
 <style>
    thead{
        position: sticky;
        top: 0;
        z-index: 100;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>คืนสถานะตู้</span></li>
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
                                <div class="mr-auto p-2">
                                    <input type="text" class="form-control" id="findJob" style="width: 30%" placeholder="ค้นหางานทั้งหมด">
                                </div>
                                <div class="table-responsive" style="height: 700px" >
                                    <table class="table table-bordered table-hover table-striped mb-4 Job" >
                                        <thead style="background-color:rgb(229, 238, 107);">
                                                <th>เลขตู้</th>
                                                <th>ทะเบียนรถ</th>
                                                <th>คนรถ/เบอร์โทร</th>
                                                <th>สถานะรับงาน</th>
                                                <th>เวลารับงาน</th>
                                                <th class="text-center">คืนสถานะตู้</th>
                                        </thead>
                                        <tbody>
                                            @if(count($Container) != 0)
                                                @foreach ($Container as $item)
                                                    @php
                                                        $Carsize = '';
                                                        switch ($item->CarType) {
                                                            case 'CT001':
                                                                $Carsize = 'รถเล็ก';
                                                                break;
                                                            case 'CT002':
                                                                $Carsize = 'รถกลาง';
                                                                break;
                                                            case 'CT003':
                                                                $Carsize = 'รถใหญ่';
                                                                break;
                                                        }
                                                    @endphp
                                                    <tr class="dataContain" data-contain="{{ $item->ContainerNo }}" >
                                                        <td>#{{ $item->ContainerNo }}</td>
                                                        <td >
                                                            {{ $item->VehicleCode }}({{ $Carsize }})
                                                        </td>
                                                        <td class="text-break">
                                                            <div class="avatar">
                                                                <img alt="avatar" src="{{ asset('theme/assets/img/90x90.jpg') }}" class="rounded-circle" />
                                                            </div>
                                                            <div>
                                                                {{ $item->EmpDriverName." ".$item->EmpDriverlastName }}<br>{{ $item->EmpDriverTel }}
                                                            </div>
                                                        </td>
                                                        <td class="text-break">
                                                            @if($item->flag_job == 'Y')
                                                                <span class="badge outline-badge-success shadow-none">รับงาน</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->ConfirmDate != '')
                                                                <span class="badge outline-badge-success shadow-none">รับงานเมื่อ : {{ ShowDate($item->ConfirmDate,"d-m-Y H:i") }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <i class="fa-solid fa-rotate-left fa-2xl returnContainer" style="color: #d70909;"></i>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else 
                                                <tr>
                                                    <td class="text-center" colspan="6"><h4>ไม่พบข้อมูล</h4></td>
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
@endsection

@section('script')

<script>
    $(document).ready(function () {
        $("#findJob").keyup(function (e) {
            let value   = $(this).val().toLowerCase();
            $(".Job tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });   
        });
        $('.returnContainer').click(function (e) { 
            e.preventDefault();
            let container =  $(this).parent().parent().data('contain');
            swal({
                title: 'ต้องการคืนตู้ ?',
                text: 'เลขตู้ :'+container,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: url+"/ReturnFlagContainer",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {container:ContainerNo},
                        // dataType: "dataType",
                        success: function (response) {
                            if(response == "success"){
                                swal({
                                    title: 'บันทึกสำเร็จ',
                                    text: '',
                                    type: 'success',
                                    padding: '2em'
                                }).then((result) => {
                                    location.reload();
                                })
                            }else{
                                swal({
                                    title: 'เกิดข้อผิดพลาด',
                                    text: response,
                                    type: 'error',
                                    padding: '2em'
                                })
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection