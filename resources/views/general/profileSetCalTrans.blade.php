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
    .form-group label, label{
        padding-top: 12px;
        font-size: 16px;
        text-align: right;
    }
    hr{
        border-top: 3px solid #000000;
        width: 80%;
    }
    .boxFomular{
        height: 600px;
        overflow: scroll;
        overflow-x: hidden;
        border: 2px solid #bbb5b5;
        padding: 10px;
        border-radius: 5px;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>รายงานเที่ยวรถ</span></li>
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
                                <form id="" action="javascript:void(0);">
                                    <div class="form-row">
                                        @php
                                            $alphas = range('A', 'Z');
                                        @endphp
                                        <div class="col-md-2 mb-4">
                                            <label>รหัสสูตร</label>
                                            <select class="form-control " name="FormulaCode">
                                                <option value=""></option>
                                                @foreach ($alphas as $item)
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-4">
                                            <label>ประเภทรถ</label>
                                            <select class="form-control " name="AreaColumn">
                                                <option value="1">CT001 | รถบรรทุก 4 ล้อเล็ก</option>
                                                <option value="2">CT003 | รถบรรทุก 6 ล้อ</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <label>รายละเอียด</label>
                                            <input type="text " class="form-control" name="FormulaDetail">
                                        </div>
                                    </div>
                                </form>
                                <div id="Formular_co_Form">
                                    <div class="boxFomular">
                                        <h5>ค่าขนส่งสินค้า(เด็กติดรถ) = A + C </h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row  mb-4">
                                                    <label for="set-A" class="col-md-2 col-form-label col-form-label-md">A (ค่าเริ่มต้น) : </label>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control form-control-md" id="set-A" name="Var_StartValue" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row  mb-4">
                                                    <label for="" class="col-md-2 col-form-label col-form-label-md">C (ค่าบรรทุก) = <br><br> D ค่าพื้นที่ </label>
                                                    <div class="col-md-3 text-center" style="border-left: 5px solid black; border-radius: 35px;">
                                                        <span>( ส.ป.ส.ปริมาตร : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Capacity"> X ปริมาตร )</span>
                                                        <span style=" position: absolute; right: -18px; top: 30px; font-size: 30px; font-weight: bold;">
                                                                    <i class="fa-solid fa-plus fa-xl"></i>
                                                        </span> 
                                                        <br><hr>
                                                        <span>ปริมาตรสูงสุด</span>
                                                    </div>
                                                    <div class="col-md-3 text-center" style="border-right: 5px solid black; border-radius: 35px;">
                                                        <span>( ส.ป.ส.น้ำหนัก : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Weight"> X น้ำหนัก )</span>
                                                    
                                                        <br><hr>
                                                        <span>น้ำหนักสูงสุด</span>
                                                    </div>
                                                    <div class="col-md-1 text-center">
                                                        <span style="position: absolute;
                                                        top: 50px;
                                                        font-size: 25px;
                                                        right: 45px;
                                                        font-weight: bold;">
                                                            <i class="fa-solid fa-x fa-xl"></i>
                                                        </span> 
                                                    </div>
                                                    <div class="col-md-2 text-center "  style="border-left: 5px solid black; border-right: 5px solid black; border-radius: 35px;">
                                                        <span>ส.ป.ส.ระยะทางที่ 1 : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Distance1"></span>
                                                        <br><hr>
                                                        <span>ส.ป.ส.ระยะทางที่ 2 : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Distance2"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="FormulaCode_h" >
                                    <input type="hidden" id="AreaColumn_h" >
                                    <input type="hidden" id="FormulaDetail_h" >
                                    <button class="btn btn-primary mt-2" type="submit">บันทึกข้อมูล</button>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  
</script>
@endsection