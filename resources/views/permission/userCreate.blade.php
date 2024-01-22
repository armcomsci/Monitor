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
    .SubMenu{
        text-indent: 2em;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>เพิ่มผู้ใช้งาน/Admin</span></li>
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
                                <div class="form-group row mt-4">
                                    <div class="col-2 mt-1">
                                        <button type="button" class="btn btn-outline-primary" id="Add">เพิ่มผู้ใช้งาน</button>
                                    </div>
                                </div>
                                <div class="table-responsive" style="height: 650px;">
                                    <table class="table table-bordered mb-4" >
                                        <thead style="background: #1b00f4">
                                            <tr>
                                               <th>รหัสพนักงาน</th>
                                               <th>ชื่อ-นามสกุล</th>
                                               <th>ตำแหน่ง</th>
                                               <th>สถานะ</th>
                                               <th class="text-center">แก้ไข/ลบ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lmUser as $item)
                                            <tr>
                                                <td>{{ $item->EmpCode }}</td>
                                                <td>{{ $item->Fullname }}</td>
                                                <td>
                                                    @php
                                                        $Flag = 'Team Admin';
                                                        if($item->type == '1'){
                                                            $Flag = 'Team Mornitor';
                                                        }
                                                    @endphp
                                                    {{ $Flag }}
                                                </td>
                                                <td>
                                                    @php
                                                        $Checked = "";
                                                        if($item->status == "Y"){
                                                            $Checked = "Checked";
                                                        }
                                                    @endphp
                                                    <label class="switch s-icons s-outline s-outline-success  mb-4 mr-2">
                                                        <input type="checkbox" {{ $Checked }} class="change_st_flag"  data-id="{{ $item->id }}">
                                                        <span class="slider"></span>
                                                    </label>
                                                </td>
                                                <td class="text-center">
                                                    <ul class="table-controls">
                                                        <li class="edit" data-id="{{ $item->id }}">
                                                            <a href="javascript:void(0);"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                            </a>
                                                        </li>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มผู้ใช้งาน/Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 300px;"> 
                <form id="SaveUser" action="javascript:void(0);" >
                    <div class="form-row">
                        <div class="col-md-3 mb-4">
                            <label>รหัสพนักงาน</label>
                            <input type="text" class="form-control required"  name="EmpCode" placeholder="รหัสพนักงาน" >
                        </div>
                        <div class="col-md-6 mb-4">
                            <label>ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control required" name="Fullname" placeholder="ชื่อ-นามสกุล" >
                        </div>
                        <div class="col-md-3 mb-4">
                            <label>ตำแหน่ง</label>
                            <select class="form-control required" name="Flag">
                                <option value="0">Admin</option>
                                <option value="1">Monitor</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label>รหัสผ่าน</label>
                            <input type="password" class="form-control required" name="password" placeholder="รหัสผ่าน" >
                        </div>
                    </div>
                    <input type="hidden" name="type" id="type">
                    <button class="btn btn-primary mt-3" type="submit">บันทึกข้อมูล</button>
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
<script src="{{ asset('theme/custom/jquery.mask.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("input[name='EmpCode']").mask('00000000');

    $('#Add').click(function (e) { 
        e.preventDefault();
        $('#AddGroup').modal('show');
        $("input[name='EmpCode']").attr('readonly',false);
        $("input[name='password']").addClass('required');
        $('#type').val('0');
        $("#SaveUser").trigger('reset');
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
            url: url+"/userChangeStatus",
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

    $('#SaveUser').submit(function (e) { 
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
                url: url+"/userSave",
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

    $('.edit').click(function(e){
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            type: "post",
            url: url+"/userGet",
            data: {'id' : id},
            // dataType: "dataType",
            success: function (response) { 
                $("input[name='EmpCode']").attr('readonly',true);
                $("input[name='EmpCode']").val(response.EmpCode);
                $("input[name='Fullname']").val(response.Fullname);
                if(response.type == 1){
                    $("select[name='Flag']").val(response.type);
                }else{
                    $("select[name='Flag']").val(0);
                }
                $("input[name='password']").removeClass('required');
                $('#type').val('1');
                $('#AddGroup').modal('show');
            }
        });
    });
</script>
@endsection