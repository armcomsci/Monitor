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
 <link rel="stylesheet" href="{{ asset('theme/assets/css/daterangepicker.css') }}">
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
                            <li class="breadcrumb-item active" aria-current="page"><span>กำหนดข้อมูลน้ำมัน</span></li>
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
                                            <button type="button" class="btn btn-outline-primary" id="Add">กำหนดค่าน้ำมัน</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive" style="height: 650px;">
                                    <table class="table table-bordered mb-4" >
                                        <thead style="background: #ddeb60">
                                            <tr>
                                               <th>ค่าน้ำมัน ณ วันที่</th>
                                               <th>ค่าน้ำมัน</th>
                                               <th>ประเภทน้ำมัน</th>
                                               <th>บริษัทผู้จำหน่าย</th>
                                               <th>วัน-เวลาที่กำหนด</th>
                                               <th class="text-center">แก้ไข/ลบ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($OilSet as $item)
                                            <tr>
                                                <td>
                                                    {{ ShowDate($item->OilDate,'d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ number_format($item->OilPrice,2) }}
                                                </td>
                                                <td>
                                                    {{  $item->OilTypeName  }} 
                                                </td>
                                                <td>
                                                    {{ $item->OilCompName }}
                                                </td>
                                                <td>
                                                    {{ ShowDate($item->SetTime) }}
                                                </td>
                                                @php
                                                    $CurentDate = date('Ymd');
                                                    $SetTime    = ShowDate($item->OilDate,'Ymd');   
                                                @endphp
                                                <td class="text-center">
                                                    @if ($CurentDate <= $SetTime)
                                                    <ul class="table-controls">
                                                        <li>
                                                            <a href="javascript:void(0);"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                            </a>
                                                        </li>
                                                    </ul>
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
<div class="modal fade" id="AddGroup" tabindex="-1" role="dialog" aria-labelledby="AddGroup" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">กำหนดค่าน้ำมัน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 200px;"> 
                <form id="configOilSave" action="javascript:void(0);" >
                    <div class="form-row">
                        <div class="col-md-3 mb-4">
                            <label>ประเภทน้ำมัน</label>
                            <select class="form-control required" name="oilType">
                                <option value=""></option>
                                @foreach ($OilType as $oil)
                                    <option value="{{ $oil->OilTypeCode }}">{{ $oil->OilTypeName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label>บริษัทผู้จำหน่าย</label>
                            <input type="text" class="form-control required" name="oilCompName" placeholder="บริษัทผู้จำหน่าย" >
                        </div>
                        <div class="col-md-2 mb-4">
                            <label>ค่าน้ำมัน ณ วันที่</label>
                            <input type="text" class="form-control required" name="OilDate" placeholder="" >
                        </div>
                        <div class="col-md-3 mb-4">
                            <label>ค่าน้ำมัน</label>
                            <input type="text" class="form-control required" name="OilPrice" placeholder="00.00" >
                        </div>
                        <input type="hidden" name="type" id="type">
                    </div>
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

    $('#Add').click(function (e) { 
        e.preventDefault();
        $('#AddGroup').modal('show');
    });

    $("input[name='OilPrice']").mask('00.00');

    $("input[name='OilDate']").daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "minDate": new Date(),
        // "autoUpdateInput": false,
        "autoApply":true,
        locale: {
            format: 'DD-MM-YYYY',
            // cancelLabel: 'Clear'
        }
    });

    $("select[name='oilType']").change(function (e) { 
        e.preventDefault();
        let val = $(this).val();

        $.ajax({
            type: "post",
            url: url+"/configOilGetComp",
            data : {'value':val},
            success: function (response) {
                $("input[name='oilCompName']").val(response.OilCompName);
                $("input[name='oilCompName']").attr('readonly',true);
            }
        });
    });

    $('#configOilSave').submit(function (e) { 
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
                url: url+"/configOilSave",
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