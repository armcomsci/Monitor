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
 <link rel="stylesheet" href="{{ asset('theme/assets/css/daterangepicker.css') }}">
 <style>
    thead{
        position: sticky;
        top: 0;
        z-index: 100;
    }
    i{
        cursor: pointer;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>เปลี่ยนคนรถ</span></li>
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
                                                <th class="text-center">สถานะรับงาน</th> 
                                                <th class="text-center">แก้ไขเวลาเข้า</th>
                                                <th class="text-center">แก้ไขเวลาออก</th>
                                        </thead>
                                        <tbody>
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
                                                    <td class="text-break text-center">
                                                        @if(empty($item->flag_job) && empty($item->flag_exit))
                                                            <span class="badge outline-badge-danger shadow-none">ยังไม่รับงาน</span>
                                                        @elseif($item->flag_job == 'Y' && empty($item->flag_exit))
                                                            <span class="badge outline-badge-success shadow-none">รับงาน</span>
                                                        @elseif($item->flag_job == 'N' && empty($item->flag_exit)) 
                                                            <span class="badge outline-badge-danger shadow-none">ปฏิเศษงาน</span>
                                                        @elseif($item->flag_exit == "Y" && $item->flag_job == "Y")
                                                            <span class="badge outline-badge-success shadow-none">เข้ารับ : {{ ShowDate($item->created_at,"d-m-Y H:i") }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->flag_job == 'Y' && empty($item->flag_exit))
                                                        <i class="fa-solid fa-right-to-bracket OldEmp fa-2xl" data-toggle="modal" data-target="#EditTimeJoin" style="color: #06e136;" data-time="join"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->flag_exit == "Y" && $item->flag_job == "Y")
                                                            <i class="fa-solid fa-right-from-bracket OldEmp fa-2xl" data-toggle="modal" data-target="#EditTimeJoin" style="color: #ef2a4b;" data-time="exit"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
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
<div class="modal fade" id="EditTimeJoin" tabindex="-1" role="dialog" aria-labelledby="EditTimeJoin" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditTimeTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="dataOldEmp text-center" style="font-size: 20px;">

                </div>
                <div class="newEmp">
                   <form id="ConfirmEmp" onSubmit="myFunctionName(); return false">
                        <div class="form-group row mt-4">
                            <label class="col-3 col-form-label"  style="text-align:right">แก้ไขเวลา : </label>
                            <div class="col-9">
                                <input type="text" id="TimeSave" class="form-control" name="TimeSave" />
                                <input type="hidden" name="container"> 
                                <input type="hidden" name="flag"> 
                            </div>
                        </div>
                   </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveChange">บันทึกข้อมูล</button>
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('theme/assets/js/daterangepicker.js') }}"></script>
<script>
    $(document).ready(function () {

        moment.locale('th');

        $('#TimeSave').daterangepicker({
            startDate: moment(), // set the initial start date
            minDate: moment().subtract(7, 'days'), // set the initial end date to today
            maxDate : moment(),
            timePicker: true, // enable time picker
            timePicker24Hour: true, // use 24-hour time format
            // timePickerIncrement: 15, // increment time by 15 minutes
            opens: 'left', // position the picker to the left of the input
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm', // set the format of the selected date range
            }
        });

        $('.OldEmp').click(function (e) { 
            e.preventDefault();
            let option  = $(this).data('time');
            let container   = $(this).parent().parent().data('contain');

            let oldEmp
            if(option == "join"){
                oldEmp      = $(this).parent().prev().prev().children().clone();
                $('#EditTimeTitle').text('แก้ไขเวลาเข้า : '+container);
                $("input[name='flag']").val(option);
            }else if(option == "exit"){
                oldEmp      = $(this).parent().prev().prev().prev().children().clone();
                $('#EditTimeTitle').text('แก้ไขเวลาออก : '+container);
                $("input[name='flag']").val(option);
            }

            $('#ContainerNo').text(container);
            $("input[name='container']").val(container);
            $('.dataOldEmp').empty();
            $('.dataOldEmp').append(oldEmp);
        });

        $('#saveChange').click(function(e){
            let TimeSave = $("select[name='TimeSave']").val();
            if(TimeSave == ""){
                swal({
                    title: 'กรุณาระบุเวลา',
                    text: '',
                    type: 'error',
                    padding: '2em'
                })
                return false;
            }else{
                $.ajax({
                    type: "post",
                    url: url+"/SaveTimeEmp",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: $('#ConfirmEmp').serialize(),
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
                    },  
                    error: function (response) {
                        swal({
                            title: 'เกิดข้อผิดพลาด',
                            text: response,
                            type: 'error',
                            padding: '2em'
                        })
                    }
                });
            }
        });

        $("#findJob").keyup(function (e) {
            let value   = $(this).val().toLowerCase();
            $(".Job tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });   
        });
    });
</script>
@endsection