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
        height: 450px;
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
                                <ul class="nav nav-tabs mb-3 mt-3" id="borderTop" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#main" role="tab" aria-controls="home-tab" aria-selected="true">สูตรคนรถ</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="home2-tab" data-toggle="tab" href="#formula_co" role="tab" aria-selected="false">สูตรเด็กติดรถ</a>
                                    </li>  
                                </ul>
                                <div class="tab-content" >
                                    <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="home-tab">
                                        <h3 class="mb-4 pt-2">สูตรคนรถ</h3>
                                        <form id="FindRoute" action="javascript:void(0);">
                                            @php
                                                $alphas = range('A', 'Z');
                                            @endphp
                                            <div class="form-row">
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
                                        <form id="FormularForm">
                                            <div class="boxFomular">
                                                <h5 >ค่าขนส่งสินค้า = A + B + C + D + E + F</h5>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="set-A" class="col-md-2 col-form-label col-form-label-md">A (ค่าเริ่มต้น) : </label>
                                                            <div class="col-md-2">
                                                                <input type="text" class="form-control form-control-md" name="Var_StartValue" id="set-A" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="set-B" class="col-md-2 col-form-label col-form-label-md">B (ค่าน้ำมัน) = </label>
                                                            <div class="col-md-3" style="padding-top: 10px; font-size: 18px;">
                                                                <p>( ราคาน้ำมัน <i class="fa-solid fa-x"></i> (ระยะทาง <i class="fa-solid fa-x "></i> 2) ) / อัตราการใช้น้ำมัน</p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="text" class="form-control form-control-md" name="var_OilUsePerKM" id="set-B" >
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
                                                                <span>( ส.ป.ส.น้ำหนัก : <input type="text" class="form-control" style="width: 35%;display: inline-block;"  name="Var_Cond_Weight"> X น้ำหนัก )</span>
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
                                            
                                                
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="set-E" class="col-md-2 col-form-label col-form-label-md">E (ลูกค้ารายละ) = </label>
                                                            <div class="col-md-2">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control form-control-md" name="Var_CustCompute" id="set-E" >
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text" >บาท</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="set-F" class="col-md-2 col-form-label col-form-label-md">F (ค่าคงที่) = </label>
                                                            <div class="col-md-2">
                                                                <input type="text" class="form-control form-control-md" name="Var_Cond_Constant" id="set-F" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="set-G" class="col-md-2 col-form-label col-form-label-md">G (จ่ายเพิ่มจากค่าน้ำมัน (ฺB)) = </label>
                                                            <div class="col-md-2">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control form-control-md" name="Var_LowCost" id="set-G" >
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text" >%</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="set-H" class="col-md-2 col-form-label col-form-label-md">H (เงินสะสมประกันภัยและการบำรุงรักษา) = </label>
                                                            <div class="col-md-2">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control form-control-md"  name="Var_ForInsure"  id="set-H" >
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text" >%</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="set-I" class="col-md-2 col-form-label col-form-label-md">I (หักเข้ากองทุนพนักงาน) = </label>
                                                            <div class="col-md-2">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control form-control-md" name="Var_Savings" id="set-I" >
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text" >%</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="FormulaCode_h" >
                                            <input type="hidden" id="AreaColumn_h" >
                                            <input type="hidden" id="FormulaDetail_h" >
                                            <button class="btn btn-primary mt-2" type="submit">บันทึกข้อมูล</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="formula_co" role="tabpanel" aria-labelledby="formula_co">
                                        <h3 class="mb-4 pt-2">สูตรเด็กติดรถ</h3>
                                        <form id="" action="javascript:void(0);">
                                            <div class="form-row">
                                                <div class="col-md-2 mb-4">
                                                    <label>รหัสสูตร</label>
                                                    <select class="form-control " name="FormulaCode_co">
                                                        <option value=""></option>
                                                        @foreach ($alphas as $item)
                                                            <option value="{{ $item }}">{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-4">
                                                    <label>ประเภทรถ</label>
                                                    <select class="form-control " name="AreaColumn_co">
                                                        <option value="1">CT001 | รถบรรทุก 4 ล้อเล็ก</option>
                                                        <option value="2">CT003 | รถบรรทุก 6 ล้อ</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-4">
                                                    <label>รายละเอียด</label>
                                                    <input type="text " class="form-control" name="FormulaDetail_co">
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
                                                                <input type="text" class="form-control form-control-md" id="set-A" name="Var_StartValue_co" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row  mb-4">
                                                            <label for="" class="col-md-2 col-form-label col-form-label-md">C (ค่าบรรทุก) = <br><br> D ค่าพื้นที่ </label>
                                                            <div class="col-md-3 text-center" style="border-left: 5px solid black; border-radius: 35px;">
                                                                <span>( ส.ป.ส.ปริมาตร : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Capacity_co"> X ปริมาตร )</span>
                                                                <span style=" position: absolute; right: -18px; top: 30px; font-size: 30px; font-weight: bold;">
                                                                            <i class="fa-solid fa-plus fa-xl"></i>
                                                                </span> 
                                                                <br><hr>
                                                                <span>ปริมาตรสูงสุด</span>
                                                            </div>
                                                            <div class="col-md-3 text-center" style="border-right: 5px solid black; border-radius: 35px;">
                                                                <span>( ส.ป.ส.น้ำหนัก : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Weight_co"> X น้ำหนัก )</span>
                                                            
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
                                                                <span>ส.ป.ส.ระยะทางที่ 1 : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Distance1_co"></span>
                                                                <br><hr>
                                                                <span>ส.ป.ส.ระยะทางที่ 2 : <input type="text" class="form-control" style="width: 35%;display: inline-block;" name="Var_Cond_Distance2_co"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="FormulaCode_co_h" >
                                            <input type="hidden" id="AreaColumn_co_h" >
                                            <input type="hidden" id="FormulaDetail_co_h" >
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
    
    $("select[name='FormulaCode']").change(function (e) { 
        e.preventDefault();
        let FormulaCode = $(this).val();
        let AreaColumn  = $("select[name='AreaColumn']").val();
        let FormulaDetail = $("input[name='FormulaDetail']").val();

        $("input[name='FormulaCode_h']").val(FormulaCode);

        $.ajax({
            type: "post",
            url: url+"/profileCalTransGet",
            data: { FormulaCode : FormulaCode },
            // dataType: "dataType",
            success: function (response) {
                if(response.length != 0){
                    console.log(response);
                    $("select[name='AreaColumn']").val(response.st_AreaColumn);
                    $("input[name='FormulaDetail']").val(response.FormulaDetail);
                    $("input[name='Var_StartValue']").val(response.Var_StartValue);
                    $("input[name='var_OilUsePerKM']").val(response.Var_OilUsePerKM);
                    $("input[name='Var_Cond_Capacity']").val(response.Var_Cond_Capacity);
                    $("input[name='Var_Cond_Weight']").val(response.Var_Cond_Weight);
                    $("input[name='Var_Cond_Distance1']").val(response.Var_Cond_Distance1);
                    $("input[name='Var_Cond_Distance2']").val(response.Var_Cond_Distance2);
                    $("input[name='Var_CustCompute']").val(response.Var_CustCompute);
                    $("input[name='Var_Cond_Constant']").val(response.Var_Cond_Constant);
                    $("input[name='Var_LowCost']").val(response.Var_LowCost);
                    $("input[name='Var_ForInsure']").val(response.Var_ForInsure);
                    $("input[name='Var_Savings']").val(response.Var_Savings); 
                }
            }
        });
    });

    $("select[name='AreaColumn']").change(function (e) { 
        e.preventDefault();
        let AreaColumn = $(this).val();
        $('#AreaColumn_h').val(AreaColumn);
        $('#AreaColumn_h').attr('name','AreaColumn_h');
    });

    $("input[name='FormulaDetail']").blur(function (e) { 
        e.preventDefault();
        let FormulaDetail = $(this).val();
        $('#FormulaDetail_h').val(FormulaDetail);
        $('#FormulaDetail_h').attr('name','FormulaDetail_h');
    });
    
    $('#FormularForm').submit(function (e) { 
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
                url: url+"/ProfileCalTransSave",
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

    // Script Formular co
    $("select[name='FormulaCode_co']").change(function (e) { 
        e.preventDefault();
        let FormulaCode = $(this).val();
        let AreaColumn  = $("select[name='AreaColumn_co']").val();
        let FormulaDetail = $("input[name='FormulaDetail_co']").val();

        $("input[name='FormulaCode_co_h']").val(FormulaCode);

        $.ajax({
            type: "post",
            url: url+"/profileSetCalTransGet",
            data: { FormulaCode : FormulaCode },
            // dataType: "dataType",
            success: function (response) {
                if(response.length != 0){
                    $("select[name='AreaColumn_co_h']").val(response.st_AreaColumn);
                    $("input[name='FormulaDetail_co_h']").val(response.FormulaDetail);
                    $("input[name='Var_StartValue_co']").val(response.Var_StartValue);
                    $("input[name='Var_Cond_Capacity_co']").val(response.Var_Cond_Capacity);
                    $("input[name='Var_Cond_Weight_co']").val(response.Var_Cond_Weight);
                    $("input[name='Var_Cond_Distance1_co']").val(response.Var_Cond_Distance1);
                    $("input[name='Var_Cond_Distance2_co']").val(response.Var_Cond_Distance2);
                }
            }
        });
    });

    $("select[name='AreaColumn_co']").change(function (e) { 
        e.preventDefault();
        let AreaColumn = $(this).val();
        $('#AreaColumn_co_h').val(AreaColumn);
        $('#AreaColumn_co_h').attr('name','AreaColumn_co_h');
    });

    $("input[name='FormulaDetail_co']").blur(function (e) { 
        e.preventDefault();
        let FormulaDetail = $(this).val();
        $('#FormulaDetail_co_h').val(FormulaDetail);
        $('#FormulaDetail_co_h').attr('name','FormulaDetail_co_h');
    });

    $('#Formular_co_Form').submit(function (e) { 
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
                url: url+"/ProfileSetCalTransSave",
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