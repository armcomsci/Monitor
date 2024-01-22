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
 <link rel="stylesheet" href="{{ asset('theme/assets/css/tables/table-basic.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/assets/css/forms/switches.css') }} ">
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
                            <li class="breadcrumb-item active" aria-current="page"><span>ข้อมูลพนักงานคนรถ</span></li>
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
                                <form id="FormAddGroup" onSubmit="return false">
                                    <div class="form-group row mt-4">
                                        <div class="col-2 mt-1">
                                            <button type="button" class="btn btn-outline-primary" id="Add">เพิ่มคนรถใหม่</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive" style="height: 650px;">
                                    <table class="table table-bordered mb-4" >
                                        <thead style="background: #f82206">
                                            <tr>
                                               <th>รหัสพนักงาน</th>
                                               <th>ชื่อ-นามสกุล</th>
                                               <th>กลุ่มพนักงาน</th>
                                               <th>รหัสบัตรพนักงาน</th>
                                               <th>เลขที่บัชชี</th>
                                               <th>เบอร์โทรศัพ</th>
                                               <th>สถานะ</th>
                                               <th>หมายเหตุ</th>
                                               {{-- <th>เก็บเงินสะสม</th> --}}
                                               <th>ประเภทรถ</th>
                                               <th class="text-center">แก้ไข</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($EmpDrive as $emp)
                                            <tr>
                                                <td>{{ $emp->EmpDriverCode }}</td>
                                                <td>{{ $emp->EmpDriverName.' '.$emp->EmpDriverLastName }}</td>
                                                <td>{{ $emp->EmpGroupName }}</td>
                                                <td>{{ $emp->EmpDriverCode }}</td>
                                                <td>{{ $emp->BankNO }}</td>
                                                <td>{{ $emp->EmpDriverTel }}</td>
                                                <td>
                                                    @php
                                                        $Checked = "";
                                                        if($emp->Active == "Y"){
                                                            $Checked = "Checked";
                                                        }
                                                    @endphp
                                                    <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                                        <input type="checkbox" {{ $Checked }} class="change_st_flag"  data-id="{{ $emp->EmpDriverCode }}">
                                                        <span class="slider"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $emp->EmpDriverRemark }}</td>
                                                {{-- <td></td> --}}
                                                <td>{{ $emp->TranspName }}</td>
                                                <td class="text-center">
                                                    <ul class="table-controls">
                                                        <li class="edit" data-id="{{ $emp->EmpDriverCode }}">
                                                            <a href="javascript:void(0);"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                            </a>
                                                        </li>
                                                        {{-- <li>
                                                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                            </a>
                                                        </li> --}}
                                                    </ul>
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
<div class="modal fade" id="AddGroup" tabindex="-1" role="dialog" aria-labelledby="AddGroup" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มพนักงานใหม่</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 570px;"> 
                <form id="SaveSetuser" action="javascript:void(0);" >
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label>รหัสพนักงาน</label>
                            <input type="text" class="form-control required"  name="EmpDriverCode" placeholder="รหัสพนักงาน" >
                        </div>
                        <div class="col-md-4 mb-4">
                            <label>รหัสบัตรพนักงาน</label>
                            <input type="text" class="form-control required" name="EmpDriverCardID" placeholder="รหัสบัตรพนักงาน" >
                        </div>
                        <div class="col-md-4 mb-4">
                            <label>กลุ่มพนักงาน</label>
                            <select class="form-control required" name="EmpGroupCode">
                                @php
                                    $GetGroupEmp = GetGroupEmp();
                                @endphp
                                @foreach ($GetGroupEmp as $empGroup)
                                    <option value="{{ $empGroup->EmpGroupCode }}">{{ $empGroup->EmpGroupName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label>ชื่อพนักงาน</label>
                            <input type="text" class="form-control required"  name="EmpDriverName" placeholder="ชื่อพนักงาน" >
                        </div>
                        <div class="col-md-4 mb-4">
                            <label>นามสกุลพนักงาน</label>
                            <input type="text" class="form-control required" name="EmpDriverLastName" placeholder="นามสกุลพนักงาน" >
                        </div>
                        <div class="col-md-4 mb-4">
                            <label>เบอร์โทรศัพท์</label>
                            <input type="text" class="form-control required" name="EmpDriverTel" placeholder="เบอร์โทรศัพท์" >
                        </div>
                        <div class="col-md-4 mb-4">
                            <label>ประเภทรถ</label>
                            <select class="form-control " name="TranspID">
                                <option value="0"></option>
                                @foreach ($EmpTrans as $empTran)
                                    <option value="{{ $empTran->TranspID }}">{{ $empTran->TranspName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8 mb-4">
                            <label>เลขที่บัชชีธนาคาร</label>
                            <input type="text" class="form-control required"  name="BankNO" placeholder="เลขที่บัชชีธนาคาร" >
                        </div>
                        <div class="col-md-2 mb-4">
                            <label>รายได้ตั้งแต่</label>
                            <input type="text" class="form-control "  name="SavingValue" placeholder="0.00">
                        </div>
                        <div class="col-md-2 mb-4">
                            <label>คิดเป็นเงินสะสม</label>
                            <select class="form-control " name="PercentType">
                                <option value="P">เปอร์เซ็นต์</option>
                                <option value="B">จำนวนเงิน</option>
                            </select>
                        </div>                
                        <div class="col-md-2 mb-4 ">
                            <input type="text" class="form-control" style="margin-top:29px;"  name="SavingAmount" placeholder="0.00%" >
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="n-chk ml-2" style="margin-top: 40px;">
                                <label class="new-control new-checkbox checkbox-primary">
                                  <input type="checkbox" class="new-control-input" name="IsSaving" value="Y">
                                  <span class="new-control-indicator"></span>เก็บเงินสะสม
                                </label>
                            </div>
                        </div>
                        <div  class="col-md-12 mb-4 ">
                            <label>หมายเหตุ</label>
                            <input type="text" class="form-control "  name="EmpDriverRemark" placeholder="หมายเหตุ" >
                        </div>
                    </div>
                    <input type="hidden" name="type" id="type">
                    <button class="btn btn-primary " type="submit">บันทึกข้อมูล</button>
                </form>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#Add').click(function (e) { 
        e.preventDefault();
        $('#AddGroup').modal('show');
        $("#SaveSetuser").trigger('reset');
        $("input[name='EmpDriverCode']").attr('readonly',false);
        $("input[name='EmpDriverCardID']").attr('readonly',false);
        $('#type').val('0');
    });

    $("input[name='SavingAmount']").blur(function (e){
        let val = $(this).val();

        if(val != ""){
            $("input[name='IsSaving']").attr('checked',true);
        }else{
            $("input[name='IsSaving']").attr('checked',false);
        }
    });

    $('.edit').click(function(e){
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            type: "post",
            url: url+"/settingUserGet",
            data: {'id' : id},
            // dataType: "dataType",
            success: function (response) {
                $("input[name='EmpDriverCode']").attr('readonly',true);
                $("input[name='EmpDriverCardID']").attr('readonly',true);

                $("input[name='EmpDriverCode']").val(response.EmpDriverCode);
                $("input[name='EmpDriverCardID']").val(response.EmpDriverCardID);
                $("input[name='EmpGroupCode']").val(response.EmpGroupCode);
                $("input[name='EmpDriverName']").val(response.EmpDriverName);
                $("input[name='EmpDriverLastName']").val(response.EmpDriverLastName);
                $("input[name='EmpDriverTel']").val(response.EmpDriverTel);
                $("input[name='BankNO']").val(response.BankNO);
                $("select[name='EmpGroupCode']").val(response.EmpGroupCode);
                $("select[name='TranspID']").val(response.TranspID);
         
                $('#type').val('1');
                $('#AddGroup').modal('show');
            }
        });
    });

    $('.change_st_flag').change(function (e) { 
        e.preventDefault();
        let id = $(this).data('id');
        let status;
        if($(this).is(':checked')){
            status = 'Y';
        }else{
            status = 'N';
        }
        $.ajax({
            type: "post",
            url: url+"/settingUserFlag",
            data: {'id':id,'status':status},
            // dataType: "dataType",
            success: function (response) {
                swal({
                    title: 'บันทึกสำเร็จ',
                    text: '',
                    type: 'success',
                    padding: '2em'
                });
            }
        });
    });

    $('#SaveSetuser').submit(function (e) { 
        e.preventDefault();
        let required = $('.required');
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
            $.ajax({
                type: "post",
                url: url+"/settingUserSave",
                data: $(this).serialize(),
                // dataType: "dataType",
                success: function (response) {
                    if(response == 'success'){
                        swal({
                            title: 'บันทึกสำเร็จ',
                            text: '',
                            type: 'success',
                            padding: '2em'
                        }).then((result) => {
                            location.reload();
                        });
                    }else if(response == 'error'){
                        swal({
                            title: 'Error',
                            text: 'รหัสดังกล่าวมีอยู่แล้ว',
                            type: 'error',
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
</script>
@endsection