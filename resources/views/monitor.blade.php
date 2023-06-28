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
<!--  END CUSTOM STYLE FILE  -->
<style>
.dataContain{
    cursor: pointer;
}
.activeTr{
    background-color: yellow;
}
.successFlag{
    background-color: #77dd77;
}
.alertFlag{
    background-color:  #ff6961;
}
.allJob > thead{
    background-color: #4793ff 
}
.event > thead{
    background-color: #ffb347;
}
.Cust > thead{
    background-color: #009688;
}
.allJob > thead > tr >th, .event > thead > tr >th, .Cust > thead > tr >th, .history > thead > tr >th {
    color: aliceblue;
}

.modal{
    z-index: 10001;
}
.swal2-container {
    z-index: 10002 !important;
}
.footer-save-transfer,#SendJobTo{
    display: none;
}
.table > tbody > tr > td{
    vertical-align: top;
}
.Send_Success{
    background-color: #2fa932 !important;
}
.Send_not{
    background: darkgrey !important;
}
.border-map{
    border: 2px solid #898989;
    padding: 3px;
    border-radius: 5%;
}
thead{
    position: sticky;
    top: 0;
    z-index: 100;
}
.loaddingModal{
    background-image: url("{{ asset('icon/truck.gif') }}");
    background-position: center;
    background-repeat: no-repeat;
    height: 100%;
    display: none;
}
.badge,.fa-triangle-exclamation{
    cursor: pointer;
}
#closeJob,#AddBillTime,.footer-job-close{
    display: none;
}
#map{
    height: 400px;
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
    top: 0px;
    background: #fff;
    border-radius: 10px;
    padding: 5px;
}
.hiddentxt:hover ~ .hiddenimg {
    display: block;
    position: absolute;
    z-index: 2;
    left: 100px;
    bottom: 0px;
    background: #fff;
    border-radius: 10px;
    padding: 5px;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>ติดตามรถ</span></li>
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
                            <div class="mail-overlay"></div>
                            <div id="mailbox-inbox" class="accordion mailbox-inbox">
                                <div class="d-flex">
                                    {{-- <div class="p-2">                                        
                                        <button type="button" class="btn btn-secondary mt-1 mb-1 ml-2">
                                            Score รวมทั้งหมด<span class="badge badge-light ml-1 ScoreJob">{{ round($Score,2) }} </span>
                                        </button>
                                    </div> --}}
                                    @php
                                        $Month     =   date('m',time());
                                    @endphp
                                    <div class="p-2">                                        
                                        <button type="button" class="btn btn-secondary mt-1 mb-1 ml-2">
                                            Score เดือน{{ MonthThai($Month) }}<span class="badge badge-light ml-1 ScoreJob">{{ round($Score,2) }} </span>
                                        </button>
                                    </div>
                                    <div class="p-2">
                                        <button type="button" class="btn btn-info mt-1 mb-1 ml-2">
                                            งานทั้งหมด : <span class="badge badge-light  CountJobAll">{{ $AllJob }} </span>
                                        </button>
                                    </div>
                                    <div class="p-2">
                                        <button type="button" class="btn btn-dark  mt-1 mb-1 ml-2 ShowJobClose" data-toggle="modal" data-target="#JobCloseData">
                                            งานที่ปิดแล้วภายในเดือน : <span class="badge badge-light CountJobClose">{{ $CountCloseJob }}</span>
                                        </button>
                                    </div>
                                    <div class="ml-auto p-2">
                                        <button class="btn btn-success mb-2 mr-2" id="CheckJobUpdate" data-toggle="modal" data-target="#JobUpdate">อัพเดทงาน
                                            <span class="badge badge-light ml-2 NewJob">{{ $CountJob }}</span>
                                        </button>
                                        <button class="btn btn-warning  mb-2" id="CheckUserOnline" data-toggle="modal" data-target="#JobTransFer"><i class="fa-solid fa-right-left"></i> โอนงาน</button>
                                        <button class="btn btn-info mb-2" id="ReturnJobTrans" data-toggle="modal" data-target="#JobReceive"><i class="fa-regular fa-envelope"></i> รับงาน  <span class="badge badge-light ml-2 NewJobReceive">{{ $JobTransFer }}</span></button>
                                        <button class="btn btn-danger mb-2" id="RejectJob" data-toggle="modal" data-target="#JobReceive"><i class="fa-solid fa-arrow-rotate-left"></i> คืนงาน  <span class="badge badge-light ml-2 RetrunJob">{{ $JobTransFer }}</span></button>
                                        <button class="btn btn-primary mb-2" style="margin-left:15px;"><i class="fa-solid fa-user"></i> ผู้ดูแล : {{ auth()->user()->Fullname }}</button>
                                        <a  href="#" class="logout">
                                            <button class="btn btn-danger mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-power"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                <ul class="nav nav-tabs  mb-3 mt-3" id="iconTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="icon-home-tab" data-toggle="tab" href="#Main" role="tab" aria-controls="Main" aria-selected="true">หน้าหลัก</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="icon-Product-tab" data-toggle="tab" href="#Product-TimeLine" role="tab" aria-controls="Product-TimeLine" aria-selected="false">สินค้า/TimeLine</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="icon-StatusJobTrans-tab" data-toggle="tab" href="#StatusJobTrans" role="tab" aria-controls="StatusJobTrans" aria-selected="false">สถานะโอนงาน</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="icon-History-tab" data-toggle="tab" href="#History" role="tab" aria-controls="History" aria-selected="false">ประวัติ</a>
                                    </li>
                                </ul>
                                <div class="tab-content " id="iconTabContent-1">
                                    <div class="tab-pane fade show active" id="Main" role="tabpanel" aria-labelledby="icon-home-tab">
                                        <div class="row">
                                            <div class="col-4 pl-4">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div>
                                                        <div class="avatar avatar-xl" style="width: 100px; height: 100px;" > 
                                                            <img alt="avatar" src=""  class="rounded ProfileDrive hiddentxt"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';" />
                                                            <span class="hiddenimg">
                                                                <img src=""  class="ProfileDrive"  style="width: 250px; height: 250px;"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"/>
                                                            </span>
                                                        </div>
                                                        <h5>ชื่อ-นามสกุล : <span id="FullNameDrive"></span> </h5>
                                                        <h5>เบอร์ติดต่อ : <span id="TelDriv"></span></h5>
                                                    </div>
                                                    <div>
                                                        <div>
                                                            <span class="badge outline-badge-warning mt-2" id="AddBillTime"></span>
                                                        </div>
                                                        <div class="mt-2">
                                                            <button class="btn btn-outline-danger" style="float: right;" id="closeJob">ปิดงาน</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="mr-auto mt-3">
                                                        <h4>ตำแหน่งรถ</h4>
                                                    </div>
                                                    <div class="p-2">
                                                        <button type="button" class="btn btn-info position-relative comment_driver">
                                                            <span><i class="fa-regular fa-comment fa-xl"></i></span>
                                                            <span class="badge badge-danger counter">0</span>
                                                        </button>
                                                    </div>
                                                    <div class="p-2">
                                                        <button class="btn btn-success mb-2 mr-2"><i class="fa-sharp fa-solid fa-road"></i> อัพเดทแผนที่</button>
                                                    </div>
                                                </div>
                                                
                                                {{-- <div id="dlgLoading" class="loadingWidget"></div> --}}
                                                <div id="map"></div>
                                            </div>
                                            <div class="col-4 ">
                                                <div class="d-flex ">
                                                    <div class="mr-auto p-2">
                                                        <input type="text" class="form-control" id="findJob" style="width: 100%" placeholder="ค้นหางานทั้งหมด">
                                                    </div>
                                                    <div class="p-2">
                                                        <a href="javascript:void(0)" id="PrevContain">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                                        </a>
                                                        <a href="javascript:void(0)" id="NextContain">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                                        </a>
                                                    </div>                                                  
                                                </div>
                                                <div class="table-responsive" style="height: 630px">
                                                    <table class="table table-bordered table-hover table-condensed mb-4 allJob">
                                                        <thead>
                                                            <tr>
                                                                {{-- <th>เลขตู้</th> --}}
                                                                <th>ทะเบียนรถ/เลขตู้</th>
                                                                <th>คนรถ/เบอร์โทร</th>
                                                                <th>สถานะตู้</th>
                                                                {{-- <th>สถานะ</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($Container as $emp)
                                                                @if ($emp->status_transfer == '' || $emp->status_transfer != 'W')
                                                                    @php
                                                                        $Carsize = '';
                                                                        switch ($emp->CarType) {
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
                                                                    <tr class="dataContain" data-contain="{{ $emp->ContainerNo }}" id="containNo-{{ $emp->ContainerNo }}">
                                                                        {{-- <td>{{ $emp->ContainerNo }}</td> --}}
                                                                        <td >
                                                                            {{ $emp->VehicleCode }}({{ $Carsize }})
                                                                        <br>{{ $emp->ContainerNo }}
                                                                        </td>
                                                                        <td class="text-break">
                                                                            <div class="avatar">
                                                                                <img alt="avatar" src="https://images.jtpackconnect.com/empdrive/{{ $emp->EmpDriverCode.".jpg" }}" class="rounded-circle hidden-list"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"  />
                                                                                <span class="hiddenimg">
                                                                                    <img  src="https://images.jtpackconnect.com/empdrive/{{ $emp->EmpDriverCode.".jpg" }}"  style="width: 250px; height: 250px;"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"/>
                                                                                </span>
                                                                            </div>
                                                                            <div>
                                                                                {{ $emp->EmpDriverName." ".$emp->EmpDriverlastName }}<br>{{ $emp->EmpDriverTel }}
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-break">
                                                                            @if(empty($emp->flag_job) && empty($emp->flag_exit))
                                                                                <span class="badge outline-badge-danger shadow-none">ยังไม่รับงาน</span>
                                                                            @elseif(empty($emp->flag_exit) && $emp->flag_job == 'Y')
                                                                                <span class="badge outline-badge-success shadow-none">รับงาน</span>
                                                                            @elseif(empty($emp->flag_exit) && $emp->flag_job == 'N') 
                                                                                <span class="badge outline-badge-danger shadow-none">ปฏิเศษงาน</span>
                                                                            @elseif($emp->flag_exit == "Y" && $emp->flag_job == "Y")
                                                                                <span class="badge outline-badge-success shadow-none">เข้ารับ : {{ ShowDate($emp->created_at,"d-m-Y H:i") }}</span>
                                                                            @elseif($emp->flag_exit == "N" && $emp->flag_job == "Y") 
                                                                                <span class="badge outline-badge-danger shadow-none">ออกงาน: {{ ShowDate($emp->updated_at,"d-m-Y H:i") }}</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="d-flex justify-content-between">
                                                    <h4>เหตุการณ์</h4>
                                                    <button class="btn btn-success mb-2 mr-2 AddEvent"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></button>
                                                </div>
                                                <div class="table-responsive" style="height: 260px">
                                                    <table class="table table-bordered table-hover table-condensed mb-4 event" style="overflow-x: auto">
                                                        <thead>
                                                            <tr>
                                                                <th>ลำดับ</th>
                                                                <th>หมายเหตุณ์</th>
                                                                <th>เวลา</th>
                                                                <th>ลบ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <h4>สถานะจัดส่ง</h4>
                                                </div>
                                                <div class="table-responsive" style="height: 300px">
                                                    <table class="table table-bordered table-hover table-condensed mb-4 Cust">
                                                        <thead>
                                                            <tr>
                                                                <th>ลำดับ</th>
                                                                <th>ลูกค้า</th>
                                                                <th>จำนวน</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Product-TimeLine" role="tabpanel">
                                        <div class="loaddingModal" style="height: 500px;"></div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="table-responsive" style="height: 630px">
                                                    <div class="text-center tracking-status-intransit">
                                                        <p class="tracking-status text-tight">สินค้าทั้งหมดในตู้ : <span id="ItemContain"></span></p>
                                                    </div>
                                                    <table class="table table-bordered table-hover table-striped mb-4 ItemOrder" style="height: auto;">
                                                        <thead style="background-color:gold;">
                                                                <th>สินค้า/รหัสสินค้า</th>
                                                                <th>จำนวน</th>
                                                                <th>หน่วย</th>
                                                                <th>ชื่อร้าน</th>
                                                                <th class="">สถานะ</th>
                                                                {{-- <th>ส่งเมื่อ</th> --}}
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div id="tracking-pre"></div>
                                                <div id="tracking" style="height: 630px; overflow-x: auto;">
                                                    <div class="text-center tracking-status-intransit">
                                                        <p class="tracking-status text-tight">Time Line</p>
                                                    </div>
                                                    <div class="tracking-list" style="overflow-x:auto">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="StatusJobTrans" role="tabpanel">
                                        <div class="loaddingModal" style="height: 500px;"></div>
                                        <div class="row">
                                            <div class="offset-1 col-10">
                                                <div class="mr-auto p-2">
                                                    <input type="text" class="form-control" id="findStatusJob" style="width: 40%" placeholder="ค้นหางาน">
                                                </div>
                                                <div class="p-2">
                                                    <span>แสดงย้อนหลัง 1 เดือน</span>
                                                </div>
                                                <div class="table-responsive" style="height: 550px">
                                                    <table class="table table-bordered table-hover table-striped mb-4 Statusjob" style="height: auto;">
                                                        <thead style="background: #25d5e4;">
                                                            <tr>
                                                                <th class="text-center">ลำดับ</th>
                                                                <th>ทะเบียนรถ/เลขตู้</th>
                                                                <th>ข้อมูลคนรถ/เบอร์โทร</th>
                                                                <th>โอนไปยัง</th>
                                                                <th>สถานะ</th>
                                                                <th>เมื่อ</th>
                                                                <th>เวลา</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="History" role="tabpanel">
                                        <div class="loaddingModal" style="height: 500px;"></div>
                                        <div class="row">
                                            <div class="offset-1 col-10">
                                                <div class="mr-auto p-2">
                                                    <input type="text" class="form-control" id="findHistory" style="width: 40%" placeholder="ค้นหางานประวัติ">
                                                </div>
                                                <div class="p-2">
                                                    <span>แสดงย้อนหลัง 1 เดือน</span>
                                                </div>
                                                <div class="table-responsive" style="height: 550px">
                                                    <table class="table table-bordered table-hover table-striped mb-4 history" style="height: auto;">
                                                        <thead style="background: #506690;">
                                                            <tr>
                                                                <th class="text-center">ลำดับ</th>
                                                                <th>รายละเอียด</th>
                                                                <th>เมื่อ</th>
                                                                <th>เวลา</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
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
</div>

{{-- อัพเดทงาน --}}
<div class="modal fade " id="JobUpdate" tabindex="-1" role="dialog" aria-labelledby="UpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="UpdateModalLabel">จำนวนงานที่ยังไม่มีผู้ดูแล</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 750px;">
                <div class="loaddingModal" ></div>
                <div class="table-responsive" style="height: 700px;">
                    <div class="mr-auto p-2">
                        <input type="text" class="form-control" id="findJobEmpty" style="width: 80%" placeholder="ค้นหางาน">
                    </div>
                    <div class="d-flex justify-content-between pl-5 pr-5">
                        <div>
                            <span id="jobSelect"></span>
                        </div>
                        <div>
                            <span id="jobAll"></span>
                        </div>
                    </div>
                    <table class="table mb-4" id="JobEpmTyPort">
                        <thead style="background: #8ce49b">
                            <tr>
                                <th class="text-center">
                                    <div class="n-chk">
                                        <label class="new-control new-checkbox checkbox-success">
                                          <input type="checkbox" class="new-control-input" id="checkAll">
                                          <span class="new-control-indicator"></span>
                                        </label>
                                    </div>
                                </th>
                                <th>ทะเบียนรถ/เลขตู้</th>
                                <th>ข้อมูลคนรถ/เลขตู้</th>
                                <th>สถานะ</th>
                                <th>เวลา รับงาน/ออกงาน</th>
                                {{-- <th>ออกจากคลัง</th> --}}
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer footer-save">
                <button type="button" class="btn btn-primary" id="saveJob"><i class="fa-regular fa-floppy-disk"></i> บันทึกข้อมูล</button>
                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

{{-- โอนงาน --}}
<div class="modal fade " id="JobTransFer" tabindex="-1" role="dialog" aria-labelledby="TranferJob" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TranferJob">โอนงานให้กับ : <span id="TransTo" class="pl-2"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height:750px;">
                <div class="table-responsive" style="height: 700px;">
                    <table class="table mb-4" id="EmpLogin">
                        <thead>
                            <th>รหัสพนักงาน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>สถานะ</th>
                            <th>เลือก</th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <div id="SendJobTo">
                        <div class="mr-auto p-2">
                            <input type="text" class="form-control" id="findJobTransfer"  style="width: 80%" placeholder="ค้นหางาน">
                        </div>
                        <div class="d-flex justify-content-between pl-5 pr-5">
                            <div>
                                <span id="jobTranSelect"></span>
                            </div>
                            <div>
                                <span id="jobTranAll">งานทั้งหมด : {{ $AllJob }}</span>
                            </div>
                        </div>
                        <table class="table mb-4" id="JobTransferPort">
                            <thead style="background: #fff9ed">
                                <tr>
                                    <th class="text-center">
                                        <div class="n-chk">
                                            <label class="new-control new-checkbox checkbox-warning">
                                            <input type="checkbox" class="new-control-input" id="checkTransAll">
                                            <span class="new-control-indicator"></span>
                                            </label>
                                        </div>
                                    </th>
                                    <th>ทะเบียนรถ</th>
                                    <th>ข้อมูลคนรถ/เบอร์ติดต่อ</th>
                                    <th>เวลาเข้า/ออก</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Container as $JobTrans)
                                    @if ($JobTrans->status_transfer == '' || $JobTrans->status_transfer != 'W')
                                        @php
                                            $Carsize = '';
                                            switch ($JobTrans->CarType) {
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
                                        <tr>
                                            <td class="text-center">
                                                <div class="n-chk">
                                                    <label class="new-control new-checkbox checkbox-warning">
                                                    <input type="checkbox" class="new-control-input" name="containerTrans[]" value="{{ $JobTrans->ContainerNo }}">
                                                    <span class="new-control-indicator"></span>
                                                    </label>
                                                </div> 
                                            </td>
                                            <td>{{ $JobTrans->VehicleCode }}({{ $Carsize }})
                                                <br>{{ $JobTrans->ContainerNo }}</td>
                                            <td>{{ $JobTrans->EmpDriverName." ".$JobTrans->EmpDriverlastName }}<br>{{ $JobTrans->EmpDriverTel }}</td>
                                            <td>
                                                @if(empty($JobTrans->flag_job) && empty($JobTrans->flag_exit))
                                                    <span class="badge outline-badge-danger shadow-none">ยังไม่รับงาน</span>
                                                @elseif(empty($JobTrans->flag_exit) && $JobTrans->flag_job == 'Y')
                                                    <span class="badge outline-badge-success shadow-none">รับงาน</span>
                                                @elseif(empty($JobTrans->flag_exit) && $JobTrans->flag_job == 'N') 
                                                    <span class="badge outline-badge-danger shadow-none">ปฏิเศษงาน</span>
                                                @elseif($JobTrans->flag_exit == "Y" && $JobTrans->flag_job == "Y")
                                                    <span class="badge outline-badge-success shadow-none">เข้ารับ : {{ ShowDate($JobTrans->created_at,"d-m-Y H:i") }}</span>
                                                @elseif($JobTrans->flag_exit == "N" && $JobTrans->flag_job == "Y") 
                                                    <span class="badge outline-badge-danger shadow-none">ออกงาน: {{ ShowDate($JobTrans->updated_at,"d-m-Y H:i") }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer footer-save-transfer">
                <div class="d-flex">
                    <div class="mr-auto p-2">
                        <button class="btn btn-outline-warning mb-2" id="backToEmp" data-toggle="tab" href="#home"><i class="flaticon-cancel-12"></i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg> ย้อนกลับ</button>
                    </div>
                    <div class="p-2">
                        <button type="button" class="btn btn-primary" id="saveJobTranfer"><i class="fa-solid fa-right-left"></i> ยืนยัน</button>
                    </div>
                    <div class="p-2">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- รับงานที่โอน --}}
<div class="modal fade " id="JobReceive" tabindex="-1" role="dialog" aria-labelledby="ReceiveTrans" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ReceiveTrans"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 750px;">
                <div class="table-responsive" style="height: 700px;">
                    <div class="mr-auto p-2">
                        <input type="text" class="form-control" id="findJobReceive" style="width: 80%" placeholder="ค้นหางาน">
                    </div>
                    <div class="d-flex justify-content-between pl-5 pr-5">
                        <div>
                            <span id="jobReceSelect"></span>
                        </div>
                        <div>
                            <span id="jobReceAll">งานทั้งหมด : </span>
                        </div>
                    </div>
                    <table class="table mb-4" id="DataJobReceive">
                        <thead style="background: #ddf5f0">
                            <tr>
                                <th class="text-center">
                                    <div class="n-chk">
                                        <label class="new-control new-checkbox checkbox-success">
                                          <input type="checkbox" class="new-control-input" id="checkAllJobReceive">
                                          <span class="new-control-indicator"></span>
                                        </label>
                                    </div>
                                </th>
                                {{-- <th>เลขตู้</th> --}}
                                <th>ทะเบียนรถ</th>
                                <th>ข้อมูลคนรถ/เบอร์ติดต่อ/สถานะ</th>
                                <th>ผู้โอน</th>
                                <th>เวลาโอน</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveJobReceive"><i class="fa-regular fa-floppy-disk"></i> บันทึกข้อมูล</button>
                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

{{-- คอมเม้นจากคนรถ --}}
<div class="modal fade " id="JobComment" tabindex="-1" role="dialog" aria-labelledby="Comment" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Comment">หมายเหตุของคนรถ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 650px;">
                <div class="table-responsive" style="height: 600px;">
                    <table class="table mb-4" id="DataJobComment">
                        <thead style="background: #ffc7c7;">
                            <tr>
                                {{-- <th>เลขตู้</th> --}}
                                <th>ลำดับ</th>
                                <th>หมายเหตุ</th>
                                <th>ร้านค้า</th>
                                {{-- <th>ที่อยู่ร้านค้า</th> --}}
                                <th>เวลา</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- รายละเอียดปิดงาน --}}
<div class="modal fade " id="ShowCloseJob" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="">สรุปงาน</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 750px;" id="ShowDetailJob">
                <div class="loaddingModal"></div>
            </div>
            <div class="modal-footer save-close-job">
                <button type="button" class="btn btn-primary" id="ConfirmCloseJob" ><i class="fa-regular fa-floppy-disk"></i> ปิดงาน</button>
                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="JobCloseData" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">งานที่ปิดแล้วภายในเดือน : </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 750px;" >
                <div class="loaddingModal"></div>
                <div class="table-responsive" style="height: 700px;" id="ContainerSuc">
                    <div class="mr-auto p-2">
                        <input type="text" class="form-control" id="findJobSuccess"  style="width: 80%" placeholder="ค้นหางาน">
                    </div>
                    <table class="table mb-4" id="AllJobClose">
                        <thead>
                            <th>ทะเบียนรถ/เลขตู้</th>
                            <th>ข้อมูลคนรถ/เบอร์ติดต่อ</th>
                            <th>เวลาปิดงาน</th>
                            <th>รายละเอียด</th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div id="ShowDataJobClose">
                </div>
            </div>
            <div class="modal-footer footer-job-close">
                <div class="d-flex">
                    <div class="mr-auto p-2">
                        <button class="btn btn-outline-warning mb-2" id="backToDataJob" data-toggle="tab" href="#home"><i class="flaticon-cancel-12"></i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg> ย้อนกลับ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="ConfirmImgCust" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="">รูปภาพร้าน <span id="CustNameImg"></span>
                    <a href="" id="LinkMap" target="_blank">
                        <img src="{{ asset('icon/location.png') }}" alt="">
                    </a>
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 750px;" id="">
                <div class="loaddingModal"></div>
                <img src="" alt="" id="imgPath" style="width: 100%; height: 700px;">
                <input type="hidden" id="CustId_Listno" data-custid="" data-listno="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ConfirmCust" id="ConfirmCustImg" data-status="Y" ><i class="fa-regular fa-floppy-disk mr-2"></i>Confirm</button>
                <button class="btn btn-outline-danger ConfirmCust" id="RejectImg" data-status="N" ><i class="fa-solid fa-xmark mr-2"></i>Reject</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
   
    moment.locale('th');
    var initExtent, map, gLayer, route, routeLayer, layerMarker, chkRoutedResult,carLayer,Sendto;
    function initialize(response) {
        $('#map').addClass('border-map');
        if(response['location'] != null){
            var points = [];
           

            map = new nostra.maps.Map("map", {
                id: "mapTest",
                logo: true,
                scalebar: true,
                slider: true,
                level: 16,
                lat: response['location'].lat,
                lon: response['location'].lon
            });

            nostra.config.Language.setLanguage(nostra.language.L);
            layerMarker = new nostra.maps.layers.GraphicsLayer(map, { id: "layerMarker" });
            gLayer      = new nostra.maps.layers.GraphicsLayer(map, { id: "gLayerPoint" });
            routeLayer  = new nostra.maps.layers.GraphicsLayer(map, { id: "routeLayer" });
            carLayer    = new nostra.maps.layers.GraphicsLayer(map, { id: "carLayer" });

            map.addLayer(gLayer);
            map.addLayer(layerMarker);
            map.addLayer(routeLayer);
            map.addLayer(carLayer);

            route = new nostra.services.network.route();
            route.country = "TH";

            map.events.load = function () {
                if(response['Route'] != null){
                    // ------------- เพิ่มจุดเริ่มต้น -----------------
                    let name,lat,lon
                    let Marker = new Array({
                        name :  "JT Branch 3",
                        lat  :  "13.858573",
                        lon  :  "100.3791033",
                        Addr :  "JT Branch 3"
                    });
                   
                    points.push([13.858573, 100.3791033]);
                    const stop = new nostra.services.network.stopPoint({
                        lat: 13.858573,
                        lon: 100.3791033,
                    })
                    route.addStopPoint(stop);
                    // ------------------------------------------

                    let a = 0;
                    response['Route'].forEach( List => {
                     
                        lat      = List.Late;
                        lon      = List.Long;
                        name     = List.CustName;

                        Marker.push({
                            name : name,
                            lat  : lat,
                            lon  : lon,
                            Addr : List.ShiptoAddr1
                        });
                        points.push([lat, lon]);
                        isFirstLoad = false;

                        const stop = new nostra.services.network.stopPoint({
                            lat: lat,
                            lon: lon,
                        })
                        route.addStopPoint(stop);
                        a++;
                    });
                    // console.log(Marker);
                    route.returnedRouteDetail = "true";
                    
                    route.solve((result) => {
                        routeLayer.clear();
                        carLayer.clear();
                        route.clearStopPoint();

                        if (result.isRouteDetailReturned == "true" || result.isRouteDetailReturned == true) {
         
                            routeLayer.addRoute(result,"#009EFF", 1);
                            
                            // //ทำการซูมแบบ focus ไปยังเส้นทางที่วาด
                            map.zoomToNetworkResult(result);

                            if (result.nostraResults != null) {
                                routeStopPoint = result.nostraResults;
                            } else {
                                routeStopPoint = result.agsResults;
                            }
                        } else {
                            if (result.nostraResults != null) {
                                routeStopPoint = result.nostraResults;
                            } else {
                                routeStopPoint = result.agsResults; 
                            }
                        }

                        var imgCarTempUrl = "https://developer-test.nostramap.com/developer/V2/images/car_topview.png";
                        carMarker = new nostra.maps.symbols.Marker({
                            url: imgCarTempUrl, width: 27, height: 50
                        });
                        carLayer.addMarker(response['location'].lat,response['location'].lon, carMarker);

                        points.push([response['location'].lat, response['location'].lon]);
                    });

                    // console.log(Marker);
                    let i = 1;
                    Marker.forEach(mark => {
                        
                        nostraCallout = new nostra.maps.Callout({ title: mark.name, content: "ตำแหน่ง : "+mark.Addr });
                        var marker = new nostra.maps.symbols.Marker(
                        {
                            url: "https://jtpackoffoods.co.th/Ecommerce/images/pin_"+i+".png",
                            width: 42, 
                            height: 42, 
                            attributes: {POI_NAME: "ตำแหน่ง", POI_ROAD: name}, 
                            callout: nostraCallout, 
                            draggable: false, 
                            isAnimateHover: true
                        });
                        layerMarker.addMarker(mark.lat, mark.lon, marker);
                        i++;
                    });
                }
            }

            setTimeout(() => {
                    map.setExtent(points)
            }, 1000);

            // hideLoading();
        }else{
            $('#map').html('<h2>ไม่พบตำแหน่ง GPS</h2>')
        }
    }
    var fullname = '{{ auth()->user()->Fullname }}';

    function dataCust(response){
        // console.log(response);
        $('.Cust tbody tr').remove();
        $('.event tbody tr').remove();
        $('#AddBillTime').empty();
        $('.ProfileDrive').removeAttr('src');
        $('#closeJob,#AddBillTime').css('display','none');
        $('.counter').text(response['Comment']);
        let html = '';
        let i = 1;
        let bg_class;
        let alertCustImg = '';
        $.each(response['Order'], function (index, value) { 
            // console.log(value);
            if(value.Flag_st == 'Y'){
                bg_class = "successFlag";
            }else if(value.Flag_st == 'N'){
                bg_class = "alertFlag";
            }
            if(value.Flag_gps == "N" || value.Flag_gps == null){
                alertCustImg = "<i class=\"fa-solid fa-triangle-exclamation fa-beat-fade fa-2xl ml-3 ConfirmImg\" id=\"Cust_"+value.CustID+"_"+value.ShipListNo+"\" style=\"color: #ff7600;\" title=\"ร้านค้ายังไม่ได้ยืนยันพิกัด\" data-custid=\""+value.CustID+"\" data-shiplistno=\""+value.ShipListNo+"\"></i>"
            }else{
                alertCustImg = '';
            }
            html += "<tr class=\""+bg_class+"\">"
            html += "<td>"+i+"</td>";
            html += "<td>"+value.CustName+alertCustImg+"</td>";
            html += "<td>"+value.SumQty+"</td>";
            // html += "<td>"+value.Flag_st+"</td>";
            html += "</tr>"
            i++;
        });
        $('.Cust tbody').append(html);
        
        if(response['Remark'] != ""){
            let html_remark = '';
            let e = 1;
            $.each(response['Remark'], function (index, value) { 
                html_remark += "<tr>";
                html_remark += "<td>"+e+"</td>";
                html_remark += "<td class=\"text-break\" >"+value.Remark+"</td>";
                html_remark += "<td><span class=\"badge outline-badge-success shadow-none\">"+moment(value.Datetime).format('LLL')+"</span></td>";
                html_remark += "<td><span class=\"badge outline-badge-danger DeleteRemark mb-2\" data-remark-id=\""+value.id+"\"><i class=\"fa-solid fa-trash\"></i></span></td>";
                html_remark += "</tr>";
                e++;
            });
            $('.event tbody').append(html_remark);
        }
        $('#ConfirmCloseJob').attr('data-container','');
        // console.log(response['Drive'].statusTrans);
        let profilePath = 'https://images.jtpackconnect.com/empdrive/'+response['Drive'].EmpDriverCode+'.jpg';
        $('.ProfileDrive').attr('src',profilePath);
        if(response['AddBill'].ContainerNO != null && response['Drive'].statusTrans != "W"){
            $('#ConfirmCloseJob').attr('data-container',response['AddBill'].ContainerNO);
            $('#closeJob,#AddBillTime').fadeIn(500);
            $('#AddBillTime').text('ส่งบิลเมื่อ : '+moment(response['AddBill'].Addbill_Time).format('D MMMM YYYY HH:mm'));
        }else if(response['Drive'].statusTrans == "W"){
            $('#AddBillTime').fadeIn(500);
            $('#AddBillTime').text('ไม่สามารถปิดงานได้ : โอนงานไม่สำเร็จ');
        }
    }

    function getDataTransfer(){
        $.ajax({
            type: "get",
            url: url+"/JobReceive",
            // data: "data",
            // dataType: "dataType",
            success: function (response) {
                $('#DataJobReceive tbody').empty();
                let html = '';
                $.each(response, function (index, value) {  
                    let html_status = '';

                    if(value.flag_job == null){
                        html_status += "<span class=\"badge outline-badge-danger shadow-none\">รอรับงาน</span>";
                    }else if(value.flag_job == 'Y' && value.flag_check == null){
                        html_status += "<span class=\"badge outline-badge-success shadow-none\">รับงาน</span>";
                    }else if(value.flag_job == 'N'  && value.flag_check == null){
                        html_status += "<span class=\"badge outline-badge-danger shadow-none\">ปฏิเศษงาน</span>";
                    }
                    else if(value.flag_check == 'Y' && value.flag_job != ''){
                        html_status += "<span class=\"badge outline-badge-success shadow-none\">เข้ารับ : "+moment(value.created_at).format('LLL')+"</span>";
                    }
                    else if(value.flag_check == 'N' && value.flag_job != ''){
                        html_status += "<span class=\"badge outline-badge-danger shadow-none\">ออกงาน : "+moment(value.updated_at).format('LLL')+"</span>";
                    }

                    html += "<tr>"
                    html += "<td><div class=\"n-chk\"><label class=\"new-control new-checkbox checkbox-success\"><input type=\"checkbox\" class=\"new-control-input\" name=\"containerRecev[]\" value=\""+value.ContainerNo+"\"><span class=\"new-control-indicator\"></span></label></div></td>"
                    html += "<td>"+value.VehicleCode+"<br>"+value.ContainerNo+"</td>"
                    html += "<td>"+value.EmpDriverName+" "+value.EmpDriverlastName+"<br>"+value.EmpDriverTel+"<br>"+html_status+"</td>"
                    html += "<td>"+value.Fullname+"</td>" 
                    html += "<td><span class=\"badge outline-badge-success shadow-none\">"+moment(value.Datetime).format('LLL')+"</span></td>"
                    html += "</tr>"
                });
                $('#DataJobReceive tbody').append(html);  

            }
        });
    }

    // function showLoading() {
    //     document.getElementById("dlgLoading").style.display = "block";
    // }
    // function hideLoading() {
    //     document.getElementById("dlgLoading").style.display = "none";
    // }

    $(document).ready(function() {
        $("#findJob").keyup(function (e) {
            let value   = $(this).val().toLowerCase();
            $(".allJob tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });   
        });

        $('#findCarType').change(function (e) { 
            e.preventDefault();
            let value = $(this).val().toLowerCase();
            $(".CarType").filter(function() {
                $(this).parent().toggle( $(this).text().toLowerCase().indexOf(value) > -1 )
            });  
        });

        $('#findJobEmpty').keyup(function (e) {
            var value = $(this).val().toLowerCase();
            $("#JobEpmTyPort tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });  
        
        });

        $('#findJobTransfer').keyup(function (e) {
            var value = $(this).val().toLowerCase();
            $("#JobTransferPort tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });  
        });

        $('#findJobReceive').keyup(function (e) {
            var value = $(this).val().toLowerCase();
            $("#DataJobReceive tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });  
        });

        $('#findHistory').keyup(function (e) {
            var value = $(this).val().toLowerCase();
            $(".history tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });  
        });
        
        $('#findJobSuccess').keyup(function (e) {
            var value = $(this).val().toLowerCase();
            $(".AllJobClose tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });  
        });

        $('.dataContain').click(function (e) { 
            e.preventDefault();
            let Container = $(this).data('contain');

            $('.dataContain').removeClass('activeTr');

            $(this).addClass('activeTr');

            $.ajax({
                type: "get",
                url: url+"/DtMonitor/"+Container,
                // data: "data",
                dataType: "json",
                beforeSend:function(){
                    // showLoading();
                    $('#map').empty();
                },
                success: function (response) {
                    initialize(response);
                    $('#FullNameDrive').text(response['Drive'].EmpDriverName+" "+response['Drive'].EmpDriverlastName)

                    var Tel = response['Drive'].EmpDriverTel.split(";");

                    var html_tel = ''
                    Tel.forEach(value => {
                        if(value != ""){
                            html_tel += '<a href=\"tel:'+value+'\" >'+value+'</a>,';
                        }
                    });
                    $('#TelDriv').html(html_tel);

                    dataCust(response);
                }   
            });
        });

        $('.AddEvent').click(function (e) { 
            e.preventDefault();
            let tr =  $('.event tbody tr').length;
            let No = tr+1;

            let html = '';
            html += "<tr>"
            html += "<td>"+No+"</td>"
            html += "<td class=\"text-break\" colspan=\"3\" ><div class=\"input-group\"><input type=\"text\" class=\"form-control\"><div class=\"input-group-append \"><button class=\"btn btn-success addRemark\"><i class=\"fa-regular fa-plus\"></i></button></div></div></td><td></td>"
            html += "</tr>";

            $('.event tbody').append(html);
        });

        $('#icon-Product-tab').click(function (e) { 
            e.preventDefault();
            let Contain = $('.dataContain.activeTr').data('contain');
            if(Contain != ""){
                $.ajax({
                    type: "get",
                    url: url+"/DtOrderItem/"+Contain,
                    // data: "data",
                    dataType: "json",
                    beforeSend:function(){
                        $('.ItemOrder tbody tr').remove();
                        $('.tracking-list').empty();
                        $('.loaddingModal').css('display','block');
                        // $('.loaddingModal').next().css('display','none');
                    },
                    success: function (response) {
                        // $('.loaddingModal').next().css('display','block');
                        $('.loaddingModal').css('display','none');
                        $('#ItemContain').text(Contain);
                    
                        let html = '';
                        let i = 1;
                  
                        $.each(response['OrderList'], function (index, value) { 
                            if(value.Flag_st == 'Y'){
                                bg_class = "shadow-none badge outline-badge-success";
                                bg_text  = "ส่งสำเร็จ";
                            }else if(value.Flag_st == 'N'){
                                bg_class = "badge outline-badge-danger shadow-none";
                                bg_text  = "ส่งไม่สำเร็จ";
                            }else{
                                bg_class = "badge outline-badge-secondary shadow-none";
                                bg_text  = "อยู่ระหว่างจัดส่ง";
                            }
                            html += "<tr>"
                            html += "<td>"+value.GoodName+"<br>"+value.GoodCode+"</td>"
                            html += "<td>"+value.GoodQty+"</td>"
                            html += "<td>"+value.GoodUnit+"</td>"
                            html += "<td class=\"text-break\">"+value.CustName+"</td>"
                            html += "<td><span class=\""+bg_class+"\">"+bg_text+"</span></td>"
                            html += "</tr>"
                            i++;
                        });
                        $('.ItemOrder tbody').append(html);
                        // console.log(response['CustList']);
                        if(response['CustList'] != ""){
                            let html_cust = '';
                            $.each(response['CustList'], function (index, value) { 
                                    // console.log(value.Flag_st_date);
                                    let statusSend = '';
                                    if(value.Flag_st == 'Y'){
                                        statusSend = 'Send_Success';
                                    }else{
                                        statusSend = 'Send_not';
                                    }
                                    html_cust += "<div class=\"tracking-item\">";
                                    html_cust += "<div class=\"tracking-icon status-intransit "+statusSend+"\">";
                                    // html_cust += "<i class=\"fa fa-circle\"></i>"
                                    html_cust += "</div>";
                                    if(value.Flag_st_date != null){
                                        html_cust += "<div class=\"tracking-date\">"+moment(value.Flag_st_date).format('D MMMM YYYY')+"<span>"+moment(value.Flag_st_date).format('HH:mm')+"</span></div>";
                                    }else{
                                        html_cust += "<div class=\"tracking-date\"></div>";
                                    }
                                
                                    html_cust += "<div class=\"tracking-content\">"+value.CustName+"<span>"+value.ShiptoAddr1+"</span></div>"
                                    html_cust += "</div>";
                            });
                            $('.tracking-list').append(html_cust);
                        }
                    }   
                });
            }
            
        });

        $('#icon-StatusJobTrans-tab').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "get",
                url: url+"/GetJobStatus",
                // data: "data",
                // dataType: "dataType",
                beforeSend : function (){
                    $('.Statusjob tbody').empty();
                    $('.loaddingModal').css('display','block');
                },
                success: function (response) {
                    $('.loaddingModal').css('display','none');
                    let i = 1;
                    let html = '';
                    $.each(response, function (index, value) { 
                        html += "<tr>"
                        html += "<td class=\"text-center\">"+i+"</td>"
                        html += "<td>"+value.VehicleCode+"<br>"+value.ContainerNo+"</td>"
                        html += "<td>"+value.EmpDriverName+" "+value.EmpDriverlastName+"<br>"+value.EmpDriverTel+"</td>"
                        html += "<td>"+value.Fullname+"</td>"
                        if(value.Status == "W"){
                            html += "<td><span class=\"badge badge-warning\">รอดำเนินการ</span></td>";
                        }else if(value.Status == "R"){
                            html += "<td><span class=\"badge badge-danger\">คืนงาน</span></td>";
                        }else if(value.Status == "Y"){
                            html += "<td><span class=\"badge badge-success\">รับงาน</span></td>";
                        }
                        html += "<td><span class=\"badge outline-badge-secondary\">"+moment(value.Datetime).fromNow()+"</span></td>"
                        html += "<td><span class=\"badge outline-badge-info\"> "+moment(value.Datetime).format('D MMMM YYYY HH:mm')+"</span></td>"
                        html += "</tr>"
                        i++;
                    });
                    $('.Statusjob tbody').append(html);
                }
            });
        });

        $('#icon-History-tab').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "get",
                url: url+"/GetHistory",
                // data: "data",
                // dataType: "dataType",
                beforeSend : function (){
                    $('.history tbody').empty();
                    $('.loaddingModal').css('display','block');
                },
                success: function (response) {
                    $('.loaddingModal').css('display','none');
                    let i = 1;
                    let html = '';
                    $.each(response, function (index, value) { 
                        html += "<tr>"
                        html += "<td class=\"text-center\">"+i+"</td>"
                        html += "<td>"+value.Detail+"</td>"
                        html += "<td><span class=\"badge outline-badge-secondary\">"+moment(value.Datetime).fromNow()+"</span></td>"
                        html += "<td><span class=\"badge outline-badge-info\"> "+moment(value.Datetime).format('D MMMM YYYY HH:mm')+"</span></td>"
                        html += "</tr>"
                        i++;
                    });
                    $('.history tbody').append(html);
                }
            });
        });

        $('#PrevContain').click(function (e) { 
            e.preventDefault();
            $('.activeTr').prev().trigger('click');
        });

        $('#NextContain').click(function (e) { 
            e.preventDefault();
            $('.activeTr').next().trigger('click');
        });

        $('#CheckJobUpdate').click(function(e){
            $(this).removeClass('blink_me');
            socket.emit('QueUpdateJob',{
                EmpCode : EmpCode
            });

            socket.on('QueJob', (data) => {

                if(data.length == 1){
                    let check_tr = $('.All_Job_Empty').length;
                
                    if(check_tr == 0){
                        $.ajax({
                            type: "get",
                            url: url+"/GetJobEmptyPort",
                            beforeSend : function (){
                                $('#JobEpmTyPort tbody').empty();
                                $('.loaddingModal').next().css('display','none');
                                $('.loaddingModal').css('display','block');
                            },
                            success: function (response) {
                                $('.loaddingModal').next().css('display','block');
                                $('.loaddingModal').css('display','none');
                                check_tr = $('.All_Job_Empty').length;
                                if(check_tr == 0){
                                    html = '';
                                    $.each(response, function (index, value) { 
                                            // console.log(value.created_at,value.updated_at)
                                            html += "<tr class=\"All_Job_Empty\">"
                                            html += "<td class=\"text-center\"><div class=\"n-chk\"><label class=\"new-control new-checkbox checkbox-success\"><input type=\"checkbox\" class=\"new-control-input\" name=\"container[]\" value=\""+value.ContainerNo+"\"><span class=\"new-control-indicator\"></span></label></div></td>";
                                            html += "<td>"+value.VehicleCode+"<br>"+value.ContainerNo+"</td>"
                                            html += "<td>ชื่อ : "+value.EmpDriverName+" "+value.EmpDriverlastName+"<br>เบอร์ : "+value.EmpDriverTel+"</td>"
                                            if(value.flag_job == ''){
                                                html += "<td><span class=\"badge outline-badge-danger shadow-none\">รอรับงาน</span></td>";
                                            }else if(value.flag_job == 'Y'){
                                                html += "<td><span class=\"badge outline-badge-success shadow-none\">รับงาน</span></td>";
                                            }else if(value.flag_job == 'N'){
                                                html += "<td><span class=\"badge outline-badge-danger shadow-none\">ปฏิเศษงาน</span></td>";
                                            }
                                            if(value.flag_check == 'Y'){
                                                html += "<td class=\"text-break\"><span class=\"badge outline-badge-success shadow-none\">"+moment(value.created_at).format('LLL')+"</span></td>";
                                            }
                                            else if(value.flag_check == 'N'){
                                                html += "<td class=\"text-break\"><span class=\"badge outline-badge-danger shadow-none\">"+moment(value.updated_at).format('LLL')+"</span></td>";
                                            }else{
                                                html += "<td></td>";
                                            }
                                            
                                            html += "</tr>"
                                    })
                                    $('#JobEpmTyPort tbody').append(html);
                                    $('#jobAll').html("งานทั้งหมด : "+$('.All_Job_Empty').length);
                                    $(".footer-save").css('display','flex');
                                }
                                
                            }   
                        });
                    }
                }else if(data[0].id != socket.id){
                    // console.log(data);
                    $('#JobEpmTyPort tbody').empty();
                    let html = "<tr><td class=\"text-center\" colspan=\"4\">ขณะนี้มีผู้ใช้งานท่านอื่นกำลังอัพเดทงานอยู่ จำนวนคิวที่รอ : "+(data.length-1)+" </td></tr>";
                    $('#JobEpmTyPort tbody').append(html);
                    $(".footer-save").css('display','none');
                }
                
            });
            
        });

        $('.comment_driver').click(function (e) { 
            e.preventDefault();
            let Contain         =   $('.dataContain.activeTr').data('contain');
            let CountComment    =   parseInt($('.counter').text());
            if(typeof Contain === 'undefined'){
                swal({
                    title: 'กรุณาระบุเลขตู้',
                    text: '',
                    type: 'error',
                    padding: '2em',
                    showConfirmButton: false
                })
               return false; 
            }
            if(CountComment == 0){
                swal({
                    title: 'ไม่พบข้อมูล',
                    text: '',
                    type: 'warning',
                    padding: '2em',
                    showConfirmButton: false
                })
                return false; 
            }
            $.ajax({
                type: "get",
                url: url+"/GetCommentJob/"+Contain,
                // data: "data",
                // dataType: "dataType",
                success: function (response) {
                    $('#DataJobComment tbody').empty();
                    html = '';
                    let i = 1;
                    $.each(response, function (index, value) {  
                        html += "<tr>"
                        html += "<td>"+i+"</td>"
                        html += "<td class=\"text-break\">"+value.Remark+"</td>"
                        html += "<td class=\"text-break\">"+value.CustName+"</td>"
                        // html += "<td class=\"text-break\">"+value.ShiptoAddr1+"</td>"
                        html += "<td><span class=\"badge outline-badge-danger shadow-none\">"+moment(value.RemarkTime).format('LLL')+"</span></td>"
                        html += "</tr>"
                        i++
                    });
                    $('#DataJobComment tbody').append(html);  
                    $('#JobComment').modal('show');
                }
            });
        });

        $('.modal').on('show.bs.modal', function (e) {
            $('input:checkbox').prop('checked',false);
        });
        
        $('#JobUpdate').on('hidden.bs.modal', function (e) {
            $('#JobEpmTyPort tbody').empty();
            socket.emit('CloseQue',{
                EmpCode : EmpCode
            });
        });

        $('#CheckUserOnline').click(function(e){
            $.ajax({
                type: "get",
                url: url+"/GetUserLogin",
                // data: "data",
                // dataType: "dataType",
                success: function (response) {
                    // console.log(response);
                    $('#EmpLogin tbody').empty();
                    html = '';
                    $.each(response, function (index, value) {  
                        html += "<tr>"
                        html += "<td>"+value.EmpCode+"</td>"
                        html += "<td>"+value.Fullname+"</td>"
                        if(value.Status_online == "Y"){
                            html += "<td><span class=\"badge badge-success inv-status\">Online</span></td>"
                        }else if(value.Status_online == "N"){
                            html += "<td><span class=\"badge badge-danger inv-status\">Offline</span></td>"
                        }
                        html += "<td><button class=\"btn btn-outline-secondary mb-2 SendtoEmp \"  ><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-right-circle\"><circle cx=\"12\" cy=\"12\" r=\"10\"></circle><polyline points=\"12 16 16 12 12 8\"></polyline><line x1=\"8\" y1=\"12\" x2=\"16\" y2=\"12\"></line></svg></button></td>"
                        html += "</tr>"
                    });
                    $('#EmpLogin tbody').append(html);  
                }
            });
        });

        $('#ReturnJobTrans').click(function (e) { 
            e.preventDefault();
            getDataTransfer();
            $('#DataJobReceive thead').css('background','#ddf5f0');
            $('#ReceiveTrans').text('ยืนยันรับงาน');
            $("#saveJobReceive").attr('data-type','Y');
        });

        $('#RejectJob').click(function (e) { 
            e.preventDefault();
            getDataTransfer();
            $('#DataJobReceive thead').css('background','#fff9ed')
            $('#ReceiveTrans').text('คืนงาน')
            $("#saveJobReceive").attr('data-type','R');
        });

        $('.ShowJobClose').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "get",
                url: url+"/GetDataJobClose",
                // data: "data",
                // dataType: "dataType",
                beforeSend : function(){
                    $('.loaddingModal').css('display','block');
                },
                success: function (response) {
                    $('#AllJobClose tbody').empty();
                    $('.loaddingModal').css('display','none');
                    html = '';
                    $.each(response, function (index, value) {  
                   
                        html += "<tr>"
                        html += "<td>"+value.VehicleCode+"<br>"+value.ContainerNo+"</td>"
                        html += "<td>"+value.EmpDriverName+" "+value.EmpDriverlastName+"<br>"+value.EmpDriverTel+"</td>"
                        html += "<td><span class=\"badge outline-badge-success shadow-none\">"+moment(value.CloseTime).format('LLL')+"</span></td>";
                        html += "<td><button class=\"btn btn-outline-secondary mb-2 CheckDetailJob \" data-contain=\""+value.ContainerNo+"\" ><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-right-circle\"><circle cx=\"12\" cy=\"12\" r=\"10\"></circle><polyline points=\"12 16 16 12 12 8\"></polyline><line x1=\"8\" y1=\"12\" x2=\"16\" y2=\"12\"></line></svg></button></td>"
                        html += "</tr>"
                    });
                    $('#AllJobClose tbody').append(html);
                }
            });
        });
    });

    $(document).on('click','.SendtoEmp',function(){
        let EmpCode = $(this).parent().prev().prev().prev().text();
        let EmpName = $(this).parent().prev().prev().text();

        $('#TransTo').html(EmpName);
        Sendto = EmpCode;
        $('#EmpLogin').css('display','none');
        $('#SendJobTo').fadeIn(1500);
        $('.footer-save-transfer').css('display','block');
    });

    $(document).on('click','.CheckDetailJob',function(){
        let ContainerNo = $(this).data('contain');
        $.ajax({
            type: "post",
            url: url+"/DataCloseJob",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {ContainerNo:ContainerNo},
            beforeSend:function(){
                // showLoading();
                $('.loaddingModal').css('display','block');
                $('#ContainerSuc').css('display','none');
            },
            success: function (response) {
                $('.loaddingModal').css('display','none');
                $('#ShowDataJobClose').html(response);
               
                $('#ShowDataJobClose').fadeIn(1500);
                $('.footer-job-close').css('display','block');
                
            }
        });
    });

    $(document).on('click','#backToDataJob',function(){
        $('#ShowDataJobClose').empty();
        $('#ShowDataJobClose').css('display','none');
        $('.footer-job-close').css('display','none');
        $('#ContainerSuc').fadeIn(1000);
    });

    $(document).on('click','#backToEmp',function(){
        $('#TransTo').empty();
        $('#EmpLogin').fadeIn(1500);
        $('#SendJobTo').css('display','none');
        $('.footer-save-transfer').css('display','none');
    });

    $(document).on('click','.addRemark', function () {
        if($('.dataContain').hasClass('activeTr')){
            let inputTag    =  $(this).parent().prev();
            let tagTd       =  $(this).parent().parent().parent();
            tagTd.attr('colspan',1);
            let tdTime      =  tagTd.next();
            let tdButtom    =  tagTd.parent();
            let remark      =  inputTag.val();
            let ContainerNo =  $('.activeTr').data('contain');
            if(remark != ""){
                $.ajax({
                    type: "post",
                    url: url+"/SaveRemark",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data : {remark : remark,container : ContainerNo},
                    success: function (response) {
                        if(response != "0"){
                            swal({
                                title: 'บันทึกสำเร็จ',
                                text: '',
                                type: 'success',
                                padding: '2em'
                            }).then((result) => {
                                tagTd.empty();
                                tagTd.text(remark)
                                tdTime.empty();
                                tdTime.append("<span class=\"badge outline-badge-success shadow-none\">"+moment().format('LLL')+"</span>");
                                tdButtom.append("<td><span class=\"badge outline-badge-danger mb-2 DeleteRemark\" data-remark-id=\""+response+"\"><i class=\"fa-solid fa-trash\"></i></span></td>");
                            })
                        }
                    }
                });
            }else{
                swal({
                    title: 'กรุณาระบุ Remark',
                    text: '',
                    type: 'error',
                    padding: '2em'
                })
            }
        }else{
            swal({
                title: 'กรุณาเลือกเลขตู้',
                text: '',
                type: 'error',
                padding: '2em'
            })
        }
    });

    $(document).on('click','.DeleteRemark',function(e){
        e.preventDefault();
        let tagTr = $(this).parent().parent();
        let text  = $(this).parent().prev().prev().text();
        let id    = $(this).data('remark-id');
        let ContainerNo =  $('.activeTr').data('contain');
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
                    url: url+"/ClearRemark",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {id:id,container:ContainerNo,text:text},
                    // dataType: "dataType",
                    success: function (response) {
                        if(response == "success"){
                            tagTr.remove();
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

    $(document).on('click','#checkAll', function () {
        $('input[name="container[]"]').not(this).prop('checked', this.checked);
        let countChecked = $('input[name="container[]"]:checked').length;
        $('#jobSelect').html("งานที่เลือก : "+countChecked);
    });

    $(document).on('click','#checkAllJobEmpty', function () {
        $('input[name="containerEmpty[]"]').not(this).prop('checked', this.checked);
        let countChecked = $('input[name="containerEmpty[]"]:checked').length;
        $('#jobEmptySelect').html("งานที่เลือก : "+countChecked);
    });

    $(document).on('click','#checkTransAll', function () {
        $('input[name="containerTrans[]"]').not(this).prop('checked', this.checked);
        let countChecked = $('input[name="containerTrans[]"]:checked').length;
        $('#jobTranSelect').html("งานที่เลือก : "+countChecked);
    });

    $(document).on('click','#checkAllJobReceive', function () {
        $('input[name="containerRecev[]"]').not(this).prop('checked', this.checked);
        let countChecked = $('input[name="containerRecev[]"]:checked').length;
        $('#jobReceSelect').html("งานที่เลือก : "+countChecked);
    });
    
    $(document).on('change','input[name="container[]"]',function(e){
        e.preventDefault();
        let countChecked = $('input[name="container[]"]:checked').length;
        $('#jobSelect').html("งานที่เลือก : "+countChecked);
    });

    $(document).on('change','input[name="containerTrans[]"]',function(e){
        e.preventDefault();
        let countChecked = $('input[name="containerTrans[]"]:checked').length;
        $('#jobTranSelect').html("งานที่เลือก : "+countChecked);
    });

    $(document).on('click','#saveJob',function(e){
        e.preventDefault();
        let container =  $('input[name="container[]"]:checked');
        if(container.length == 0){
            swal({
                title: 'กรุณาเลือกรายการ',
                text: '',
                type: 'error',
                padding: '2em'
            })
        }else{
            swal({
                title: 'ต้องการรับงาน  ?',
                text: "จำนวนงาน : "+container.length,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: url+"/SaveJob",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: container.serialize(),
                        beforeSend:function(){
                            $('#saveJob').attr('disabled',true);                            
                            // $('.save-close-job').css('display','none')
                            // showLoading();
                            // $('#ShowDetailJob').empty();
                        },
                        // dataType: "dataType",
                        success: function (response) {
                            $('#saveJob').attr('disabled',false);
                            if(response.status == "success"){
                                swal({
                                    title: 'บันทึกสำเร็จ',
                                    text: '',
                                    type: 'success',
                                    padding: '2em'
                                }).then((result) => {
                                    socket.emit('UpdateJobPort',{
                                        CountJob : response.CountJob
                                    });
                                    location.reload();
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
            })
        }
    });

    $(document).on('click','#saveJobTranfer',function(e){
        e.preventDefault();
        let containerTrans =  $('input[name="containerTrans[]"]:checked').map(function () {
                                    return this.value;
                                }).get();
 
        if(containerTrans.length == 0){
            swal({
                title: 'กรุณาเลือกรายการ',
                text: '',
                type: 'error',
                padding: '2em'
            })
        }else{
            swal({
                title: 'ต้องการโอนงานไปยัง '+$('#TransTo').text()+' ?',
                text: "งานที่ต้องการโอน : "+containerTrans.length,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: url+"/SaveTransferJob",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {'containerTrans':containerTrans,'sendto':Sendto},
                        beforeSend:function(){
                            $('#saveJobTranfer').attr('disabled',true);                            
                            // $('.save-close-job').css('display','none')
                            // showLoading();
                            // $('#ShowDetailJob').empty();
                        },
                        // dataType: "dataType",
                        success: function (response) {
                            $('#saveJobTranfer').attr('disabled',false); 
                            if(response == "success"){
                                swal({
                                    title: 'บันทึกสำเร็จ',
                                    text: '',
                                    type: 'success',
                                    padding: '2em'
                                }).then((result) => {
                                    socket.emit('SendJobTo',{
                                        SendForm : EmpCode,
                                        SendFormName : fullname,
                                        Sendto : Sendto,
                                        Amount : containerTrans.length
                                    });
                                    location.reload();
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
            })
        }
    });

    $(document).on('click','#saveJobReceive',function(e){
        e.preventDefault();
        let containerRecev =  $('input[name="containerRecev[]"]:checked').map(function () {
                                    return this.value;
                                }).get();
                                
        let type           = $(this).data('type');
        let SwalTxt;
        if(type == "Y"){
            SwalTxt = "ยืนยันรับงาน ?";
        }else if(type == "R"){
            SwalTxt = "ยืนยันคืนงาน ?";
        }

        if(containerRecev.length == 0){
            swal({
                title: 'กรุณาเลือกรายการ',
                text: '',
                type: 'error',
                padding: '2em'
            })
        }else{
            swal({
                title: SwalTxt,
                text: "จำนวนงาน : "+containerRecev.length,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: url+"/SaveReceiveJob",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {'containerRecev':containerRecev,'type':type},
                        beforeSend:function(){
                            $('#saveJobReceive').attr('disabled',true);                            
                            // $('.save-close-job').css('display','none')
                            // showLoading();
                            // $('#ShowDetailJob').empty();
                        },
                        // dataType: "dataType",
                        success: function (response) {
                            $('#saveJobReceive').attr('disabled',false);  
                            if(response.status == "success"){
                                swal({
                                    title: 'บันทึกสำเร็จ',
                                    text: '',
                                    type: 'success',
                                    padding: '2em'
                                }).then((result) => {
                                    socket.emit('StatusJobTrans',{
                                        // Receive : response.EmpCode,
                                        Amount : response.CountJob,
                                        Portname : response.PortName,
                                        container : response.container,
                                        Status : type
                                    });
                                    location.reload();
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
            })
        }
    });

    $(document).on('click','#closeJob',function(e){
        e.preventDefault();
        $('#ShowCloseJob').modal('show');
        
        let ContainerNo = $('.activeTr').data('contain');
        $.ajax({
            type: "post",
            url: url+"/DataCloseJob",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {ContainerNo:ContainerNo},
            beforeSend:function(){
                $('.loaddingModal').css('display','block');
                $('#ConfirmCloseJob').attr('disabled',true);
                // $('.save-close-job').css('display','none')
                // showLoading();
                // $('#ShowDetailJob').empty();
            },
            success: function (response) {
                $('#ConfirmCloseJob').attr('disabled',false);
                // $('.save-close-job').css('display','block')
                $('.loaddingModal').css('display','none');
                $('#ShowDetailJob').html(response);
            }
        });
    });

    $(document).on('click','#ConfirmCloseJob',function(e){
        e.preventDefault();
        let ContainerNo = $(this).data('container');
        $.ajax({
            type: "post",
            url: url+"/ConfirmCloseJob",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                if(response.status == "success"){
                    $('#ShowCloseJob').modal('hide');
                    let score = 0;
                    $.each(response.Score, function (index, value) {  
                        if(value.EmpCode == EmpCode){
                            score    = parseFloat(value.Score);
                            let ScoreJob = parseFloat($('.ScoreJob').text());
                            let SumScore = score+ScoreJob;
                            $('.ScoreJob').text(SumScore.toFixed(2));
                            return;
                        }
                    });
                    let CountEmp = response.Score.length;
                    let textSwal = '';
                    if(CountEmp > 1){
                        textSwal = " จากการทำงานร่วม "+CountEmp+" ท่าน";
                    }
                    Swal.fire({
                        imageUrl: url+'/icon/award.gif',
                        imageHeight: 250,
                        text: "ยินดีด้วยคุณได้รับคะแนน : "+score+" คะแนน"+textSwal
                    }).then((result) => {
                        $('.activeTr,#FullNameDrive,#TelDriv').empty();
                        $('.Cust tbody tr').remove();
                        $('.event tbody tr').remove();
                        $('#AddBillTime').empty();
                        $('#closeJob,#AddBillTime').css('display','none');
                        $('#map').empty();

                        $('.CountJobAll').text($('.allJob tbody tr').length);
                       
                        socket.emit('UpdateScore',{
                            // EmpAll : response.Port,
                            Score : response.Score
                        });

                        let CountClose = parseInt($('.CountJobClose').text())+1;
                        $('.CountJobClose').text(CountClose);

                        let CountJobAll = parseInt($('.CountJobAll').text())-1;
                        $('.CountJobAll').text(CountJobAll);
                    });
                }else{
                    swal({
                        title: 'Error',
                        text: response.text,
                        type: 'error',
                        padding: '2em'
                    })
                }
            },
            error: function (response){
                swal({
                    title: 'Error',
                    text: response.text,
                    type: 'error',
                    padding: '2em'
                })
            }
        });
    })

    $(document).on('click','.ConfirmImg',function(e){
        $('#ConfirmImgCust').modal('show');
        let ShipListNo = $(this).data('shiplistno');
        let CustID     = $(this).data('custid');

        $.ajax({
            type: "post",
            url: url+"/ImgCust",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {ShipListNo:ShipListNo,CustID:CustID},
            beforeSend:function(){
                $('.loaddingModal').css('display','block');
                $('#ConfirmCustImg,#RejectImg').attr('disabled',true);
                $('#imgPath').attr('src','');
                // $('.save-close-job').css('display','none')
                // showLoading();
                // $('#ShowDetailJob').empty();
            },
            success: function (response) {
            
                $('.loaddingModal').css('display','none');
                $('#ConfirmCustImg,#RejectImg').css('display','block');
                // console.log(response);
                let imgPath 
                if(typeof response.ImgPath !== 'undefined'){
                    imgPath = "https://xm.jtpackconnect.com/transport/public/"+response.ImgPath;
                    $('#CustNameImg').text(response.CustName);
                    $('#ConfirmCustImg,#RejectImg').attr('disabled',false);
                    $('#CustId_Listno').attr('data-custid',CustID)
                    $('#CustId_Listno').attr('data-listno',ShipListNo)
                    let LinkMap =  "https://www.google.com/maps/search/?api=1&query="+response.Latitude+","+response.Longitude+"";
                    $('#LinkMap').attr('href',LinkMap)
                }else{
                    imgPath = url+"/image/not_img.jpg";
                    $('#ConfirmCustImg,#RejectImg').css('display','none');
                    $('#LinkMap').attr('href','#')
                    $('#CustNameImg').text('');
                }   
                $('#imgPath').attr('src',imgPath);
            }
        });

    });

    $(document).on('click','.ConfirmCust',function(e){
        let status = $(this).data('status');
        let custid = $('#CustId_Listno').data('custid');
        let shipno = $('#CustId_Listno').data('listno');

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
                        url: url+"/ConfirmImgCust",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {custid:custid,shipno:shipno,status:status},
                        beforeSend:function(){
                            // $('.loaddingModal').css('display','block');
                            $('#ConfirmCustImg,#RejectImg').attr('disabled',true);
                            
                        },
                        success: function (response) {
                            $('#ConfirmCustImg,#RejectImg').attr('disabled',false);

                            let icon = $('#Cust_'+custid+"_"+shipno);
                            icon.remove();

                            if(response == "success"){
                                $('#ConfirmImgCust').modal('hide');
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
    });

    socket.on('UpdateJobCount',(data)=>{
        // console.log(data);
        $('.NewJob').html(data.CountJob);
    });

    socket.on('CountJob',(data) => {
        // console.log(data);
        if(data != ""){
            if(data['res']['recordset']['0'].Job >= 1){
                $('.NewJob').parent().addClass('blink_me');
                $('.NewJob').html(data['res']['recordset']['0'].Job);
            }   
        }
    });

    socket.on('StatusJobReceive',(data)=>{
            // console.log(data);
            let display =  '';
            if(data.Status == "R"){
                display = 'revert';
            }else if(data.Status == "Y"){
                display = 'none';
            }
            $.each(data['container'], function (index, value) { 
                $('#containNo-'+value).css('display',display);
            });
            $('.CountJobAll').text($('.allJob tbody tr').length);
            // if(data.Receive == EmpCode){
            //     let html = "ผู้ใช้งาน : "+data.Portname
            //     if(data.Status == "R"){
            //         let status = "คืนงานกลับ";
            //         let color  = "#e95f2b";
            //     }else if(data.Status == "Y"){
            //         let status = "รับงานที่โอน";
            //         let color  = "#009688";
            //     }
            //     Snackbar.show({
            //         text: "<div style=\"padding:10px\" >"+html+"</div><div style=\"padding:10px\" > "+status+" จำนวน "+data.Amount+" !</div>",
            //         pos: 'top-right',
            //         maxWidth: '100%',
            //         actionTextColor: '#fff',
            //         backgroundColor: '#2196f3',
            //         duration: 5000,
            //         actionText: 'X'
            //     });
            // }
    });
</script>
@endsection