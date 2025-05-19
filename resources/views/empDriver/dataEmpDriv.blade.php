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
    label{
        color: black !important;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>ข้อมูลรถ</span></li>
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
                                        <button type="button" class="btn btn-outline-primary" id="Add">เพิ่มข้อมูลรถใหม่</button>
                                    </div>
                                </div>
                                <div class="table-responsive" style="height: 650px;">
                                    <div class="col-3 mb-3">
                                        <input type="text" class="form-control" id="searchInput" placeholder="ค้นหา....">
                                    </div>
                                    <table class="table table-bordered mb-4" id="DataEmpDriv">
                                        <thead style="background: #f89c33">
                                            <tr>
                                               <th>ทะเบียนรถ</th>
                                               <th>รหัส/ชื่อ-นามสกุล</th>
                                               <th>ประเภทรถ</th>
                                               <th>สถานะรถ</th>
                                               <th class="text-center">ประวัติแก้ไข<br>อนุมัติ/ปฏิเสธ</th>
                                               <th>เวลาแก้ไขล่าสุด</th>
                                               <th class="text-center">แก้ไข/ลบ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($CarDetail as $item)
                                                <tr>
                                                    <td>{{ $item->VehicleCode }}</td>
                                                    <td>{{ $item->EmpDriverCode.' : '.$item->EmpDriverName.' '.$item->EmpDriverLastName }}</td>
                                                    <td>{{ $item->CarTypeName }}</td>
                                                    <td>
                                                        @php
                                                            $Checked = "";
                                                            if($item->IsDefault == "Y"){
                                                                $Checked = "Checked";
                                                            }
                                                        @endphp
                                                        <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                                            <input type="checkbox" {{ $Checked }} class="change_st_flag" >
                                                            <span class="slider"></span>
                                                        </label>
                                                    </td>
                                                    <td class="text-center">
                                                        <span style="color:green" >{{ $item->confirmCount }}</span> / <span style="color: red" >{{ $item->rejectCount }}</span>
                                                    </td>
                                                    <td>
                                                        @if($item->createdEdit != "")
                                                            {{ ShowDate($item->createdEdit,"d-m-Y H:i") }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <ul class="table-controls">
                                                            @if ($item->status_confirm == 'N' )
                                                            <li>
                                                                <h5 style="color:red">รออนุมัติ</h5>
                                                            </li>
                                                            @else
                                                            <li class="edit" data-id="{{ $item->VehicleCode }}">
                                                                <a href="javascript:void(0);"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                                </a>
                                                            </li>     
                                                            @endif
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

<div class="modal fade" id="AddGroup" tabindex="-1" role="dialog" aria-labelledby="AddGroup" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มข้อมูลรถใหม่</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 770px;"> 
                <ul class="nav nav-tabs mb-3 mt-3" id="borderTop" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="data-main-tab" data-toggle="tab" href="#data-main" role="tab" aria-controls="border-top-home" aria-selected="true">ข้อมูลทั่วไป</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="Isue-tap" data-toggle="tab" href="#dataIsue" role="tab" aria-controls="border-top-profile" aria-selected="false">ข้อมูลประกันภัยและ พรบ.</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="Decline-tap" data-toggle="tab" href="#dataDecline" role="tab" aria-controls="border-top-contact" aria-selected="false">ข้อมูลค่าเสื่อมสึกหรอ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="Finance-tap" data-toggle="tab" href="#dataFinance" role="tab" aria-controls="border-top-contact" aria-selected="false">ข้อมูลค่าเช่ารถ</a>
                    </li>
                </ul>
                <form id="FormCarDriv">
                    <div class="tab-content" >
                        <div class="tab-pane fade show active" id="data-main" role="tabpanel" aria-labelledby="border-top-home-tab">
                            <div class="form-row">
                                <div class="col-md-3 mb-4">
                                    <label>ทะเบียนรถ</label>
                                    <input type="text" class="form-control required"  name="VehicleCode" placeholder="ทะเบียนรถ" >
                                </div>
                                @php
                                    $CarsType = GetCarType();
                                @endphp
                                <div class="col-md-3 mb-4">
                                    <label>ประเภทรถ</label>
                                    <select class="form-control required" name="CarTypeCode">
                                        @foreach ($CarsType as $CarType)
                                            <option value="{{ $CarType->CarTypeCode.":".$CarType->CarTypeName }}">{{ $CarType->CarTypeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $Provinces = GetProvince();
                                @endphp
                                <div class="col-md-2 mb-4">
                                    <label>จังหวัด</label>
                                    <select class="form-control required" name="ProvinceID">
                                        @foreach ($Provinces as $Province)
                                            <option value="{{ $Province->ProvinceID.":".$Province->ProvinceName }}">{{ $Province->ProvinceName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $Brands = GetCarBrand();
                                @endphp
                                <div class="col-md-2 mb-4">
                                    <label>ชื่อยี่ห้อรถ</label>
                                    <select class="form-control required" name="CarBrandCode" id="CarBrandCode" data-count="0">
                                        <option value=""></option>
                                        @foreach ($Brands as $Brand)
                                            <option value="{{ $Brand->CarBrandCode.":".$Brand->CarBrandName }}">{{ $Brand->CarBrandName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-4">
                                    <label>ชื่อรุ่นรถ</label>
                                    <select class="form-control required" name="CarSerieCode">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>วันที่จดทะเบียน</label>
                                    <input type="text" class="form-control required"  name="RegistDate" id="RegistDate" data-count="0" placeholder="วันที่จดทะเบียน" >
                                </div>
                                <div class="col-md-2 mb-4">
                                    <label>ปีของรถ</label>
                                    <input type="text" class="form-control required" name="Year" placeholder="ปีของรถ" >
                                </div>
                                <div class="col-md-2 mb-4">
                                    <label>สีของรถ</label>
                                    <input type="text" class="form-control required" name="Color" placeholder="สีของรถ" >
                                </div>
                                <div class="col-md-2 mb-4">
                                    <label>หมายเลขเครื่อง</label>
                                    <input type="text" class="form-control required" name="VehicleNo"  placeholder="หมายเลขเครื่อง" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>หมายเลขตัวถัง</label>
                                    <input type="text" class="form-control required" name="BodyNo"  placeholder="หมายเลขตัวถัง" >
                                </div>
                                @php
                                    $Oils = GetOilType();
                                @endphp
                                <div class="col-md-2 mb-4">
                                    <label>ประเภทน้ำมัน</label>
                                    <select class="form-control required" name="OilTypeCode">
                                        <option value=""></option>
                                        @foreach ($Oils as $Oil)
                                            <option value="{{ $Oil->OilTypeCode.":".$Oil->OilTypeName }}">{{ $Oil->OilTypeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>บริษัทผู้ผลิต</label>
                                    <input type="text" class="form-control required"   placeholder="บริษัทผู้ผลิต" value="บริษัท เจ.ที แพ็ค ออฟ ฟู๊ดส์ จำกัด" readonly >
                                </div>
                                <div class="col-md-2 mb-4">
                                    <label>ประเภทรถขนส่ง</label>
                                    <select class="form-control" name="CarComp">
                                        <option value="Y:รถบริษัท">รถบริษัท</option>
                                        <option value="N:รถร่วมบริษัท">รถร่วมบริษัท</option>
                                        <option value="P:รถร่วมส่วนบุคคล">รถร่วมส่วนบุคคล</option>
                                        <option value="C:รถร่วมนิติบุคคล">รถร่วมนิติบุคคล</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>ชื่อนิติบุคคล</label>
                                    <input type="text" class="form-control" readonly  placeholder="ชื่อนิติบุคคล" >
                                </div>
                                @php
                                    $GetFormula = GetFormula();
                                    $GetFormula_co = GetFormula_co();
                                @endphp
                                <div class="col-md-4 mb-4">
                                    <label>สูตรคำนวณค่าขนส่ง</label>
                                    <select class="form-control required" name="FormulaCode">
                                        <option value=""></option>
                                        @foreach ($GetFormula as $Formula)
                                            <option value="{{ $Formula->FormulaCode.":".$Formula->FormulaDetail }}">{{ $Formula->FormulaDetail }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $GetEmpDriv = GetEmpDriv();

                                @endphp
                                <div class="col-md-8 mb-4">
                                    <label>เจ้าของรถ</label>
                                    <select class="form-control" name="EmpDriv" id="EmpDriv" data-count="0">
                                        <option value=""></option>
                                        @foreach ($GetEmpDriv as $EmpDriv)
                                            <option value="{{ $EmpDriv->EmpDriverCode }}">{{ $EmpDriv->EmpDriverCode." : ".$EmpDriv->EmpDriverName." ".$EmpDriv->EmpDriverLastName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>ชื่อ-นามสกุลเด็กติดตรถ</label>
                                    <input type="text" class="form-control required" name="CoEmpName"  placeholder="ชื่อ-นามสกุลเด็กติดตรถ" >
                                </div>
                                <div class="col-md-2 mb-4">
                                    <label>เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control required" name="CoTel" placeholder="เบอร์โทรศัพท์" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>สูตรคำนวณค่าขนส่งเด็กติดรถ</label>
                                    <select class="form-control" name="CoFormulaCode">
                                        <option value=""></option>
                                        @foreach ($GetFormula_co as $Formula_co)
                                            <option value="{{ $Formula_co->FormulaCode.":".$Formula_co->FormulaDetail }}">{{ $Formula_co->FormulaDetail }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>GPS</label>
                                    <input type="text" class="form-control" name="GPS"  placeholder="GPS" >
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label>หมายเหตุ</label>
                                    <input type="text" class="form-control " name="Remark"  placeholder="หมายเหตุ" >
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dataIsue" role="tabpanel" aria-labelledby="border-top-home-tab">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <h3>ข้อมูลประกันภัย</h3>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>เลขที่กรมธรรม์(ข้อมูลประกันภัย)</label>
                                    <input type="text" class="form-control required"  name="PolicyInsureNo" placeholder="เลขที่กรมธรรม์" >
                                </div>
                                @php
                                    $GetInsuranceType = GetInsuranceType();
                                @endphp
                                <div class="col-md-3 mb-4">
                                    <label>ประเภทประกันภัย(ข้อมูลประกันภัย)</label>
                                    <select class="form-control" name="InsureTypeCode">
                                        <option value=""></option>
                                        @foreach ($GetInsuranceType as $InsuranceType)
                                            <option value="{{ $InsuranceType->InsureTypeCode.":".$InsuranceType->InsureTypeName }}">{{ $InsuranceType->InsureTypeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $GetInsurance = GetInsurance();
                                @endphp
                                <div class="col-md-5 mb-4">
                                    <label>บริษัทประกันภัย(ข้อมูลประกันภัย)</label>
                                    <select class="form-control" name="InsureCompCode">
                                        <option value=""></option>
                                        @foreach ($GetInsurance as $Insurance)
                                            <option value="{{ $Insurance->InsureCompCode.":".$Insurance->InsureCompName }}">{{ $Insurance->InsureCompName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 mb-4">
                                    <label>ประกันภัย ตั้งแต่วันที่เริ่ม - ถึงวันที่</label>
                                    <input type="text" class="form-control required Time" data-count="0" id="InsureStart_End_Date"  name="InsureStart_End_Date" placeholder="เลขที่กรมธรรม์" >
                                </div>
                            
                                <div class="col-md-3 mb-4">
                                    <label>วงเงินประกัน(ข้อมูลประกันภัย)</label>
                                    <input type="text" class="form-control required"  name="InsureCap" placeholder="วงเงินประกัน" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>เบี้ยประกัน(ข้อมูลประกันภัย)</label>
                                    <input type="text" class="form-control required"  name="InsureValue" placeholder="เบี้ยประกัน" >
                                </div>
                            </div>
                            <div class="form-row mt-5">
                                <div class="col-md-12">
                                    <h3>ข้อมูล พรบ.</h3>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>เลขที่กรมธรรม์(ข้อมูล พรบ.)</label>
                                    <input type="text" class="form-control required"  name="FormulateNO" placeholder="เลขที่กรมธรรม์" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ประเภทประกันภัย(ข้อมูล พรบ.)</label>
                                    <select class="form-control" name="InsureTypeCode_Form">
                                        <option value=""></option>
                                        @foreach ($GetInsuranceType as $InsuranceType)
                                            <option value="{{ $InsuranceType->InsureTypeCode.":".$InsuranceType->InsureTypeName }}">{{ $InsuranceType->InsureTypeName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 mb-4">
                                    <label>บริษัทประกันภัย(ข้อมูล พรบ.)</label>
                                    <select class="form-control" name="InsureCompCode_Form">
                                        <option value=""></option>
                                        @foreach ($GetInsurance as $Insurance)
                                            <option value="{{ $Insurance->InsureCompCode.":".$Insurance->InsureCompName }}">{{ $Insurance->InsureCompName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 mb-4">
                                    <label>ข้อมูล พรบ. ตั้งแต่วันที่เริ่ม - ถึงวันที่</label>
                                    <input type="text" class="form-control required Time" data-count="0" id="Insure_Form_Start_End_Date" name="Insure_Form_Start_End_Date" placeholder="วันที่เริ่ม" >
                                </div>
                            
                                <div class="col-md-3 mb-4">
                                    <label>เป็นเงิน(ข้อมูล พรบ.)</label>
                                    <input type="text" class="form-control required"  name="FormulateValue" placeholder="เป็นเงิน" >
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dataDecline" role="tabpanel" aria-labelledby="border-top-home-tab">
                            <div class="form-row">
                                <div class="col-md-6 mb-4">
                                    <label>บริษัทการเงิน(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    @php
                                        $GetFinComp = GetFinComp();
                                    @endphp
                                    <select class="form-control" name="FinanceCode">
                                        <option value=""></option>
                                        @foreach ($GetFinComp as $FinComp)
                                            <option value="{{ $FinComp->FinanceCode.":".$FinComp->FinanceName }}">{{ $FinComp->FinanceName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label>สัญญาเลขที่(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control"  name="ContractNo" placeholder="สัญญาเลขที่" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ราคารถ(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarValue" placeholder="ราคารถ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ราคารถคงเหลือ(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarTotalValue" placeholder="ราคารถคงเหลือ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>เงินดาวน์(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarDownValue" placeholder="เงินดาวน์" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ดอกเบี้ย(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarInterest" placeholder="ดอกเบี้ย" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>เงินกู้(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarLoanValue" placeholder="เงินกู้" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ชำระงวดละ(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarInstallmentValue" placeholder="ชำระงวดละ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>จำนวน(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarInstallment" placeholder="จำนวน" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>งวดคงเหลือ(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarInstallmentLeft" placeholder="งวดคงเหลือ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>รวมเป็นเงิน(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control required"  name="CarBalanceValue" placeholder="รวมเป็นเงิน" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>สถานะการชำระ(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <select class="form-control" name="CarFinStatus">
                                        <option value="กำลังผ่อนชำระอยู่">กำลังผ่อนชำระอยู่</option>
                                        <option value="ผ่อนชำระหมดแล้ว">ผ่อนชำระหมดแล้ว</option>
                                        <option value="ไม่ได้ผ่อนชำระกับบริษัท">ไม่ได้ผ่อนชำระกับบริษัท</option>
                                    </select>
                                </div>
                                <div class="col-md-5 mb-4">
                                    <label>ค่าเสื่อมสึกหรอ ตั้งแต่วันที่เริ่ม - ถึงวันที่</label>
                                    <input type="text" class="form-control required Time"  data-count="0" id="CarFin_Start_End_Date" name="CarFin_Start_End_Date" placeholder="วันที่เริ่ม" >
                                </div>
                            
                                <div class="col-md-12 mb-4">
                                    <label>หมายเหตุ(ข้อมูลค่าเสื่อมสึกหรอ)</label>
                                    <input type="text" class="form-control "  name="CarFinRemark" placeholder="หมายเหตุ" >
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dataFinance" role="tabpanel" aria-labelledby="border-top-home-tab">
                            <div class="form-row">
                                <div class="col-md-3 mb-4">
                                    <label>ราคารถ(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompCarValue" placeholder="ราคารถ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ราคารถคงเหลือ(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompCarTotal" placeholder="ราคารถคงเหลือ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>เงินดาวน์(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompDownValue" placeholder="เงินดาวน์" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ดอกเบี้ย(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompCarInterest" placeholder="ดอกเบี้ย" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>เงินกู้(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompCarLoan" placeholder="เงินกู้" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>ชำระงวดละ(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompInstallmentValue" placeholder="ชำระงวดละ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>จำนวน(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompInstallment" placeholder="จำนวน" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>งวดคงเหลือ(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompInstallmentLeft" placeholder="งวดคงเหลือ" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>รวมเป็นเงิน(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control required"  name="CompCarBalance" placeholder="รวมเป็นเงิน" >
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label>สถานะการชำระ(ข้อมูลค่าเช่ารถ)</label>
                                    <select class="form-control" name="CarCompStatus">
                                        <option value="กำลังผ่อนชำระอยู่">กำลังผ่อนชำระอยู่</option>
                                        <option value="ผ่อนชำระหมดแล้ว">ผ่อนชำระหมดแล้ว</option>
                                        <option value="ไม่ได้ผ่อนชำระกับบริษัท">ไม่ได้ผ่อนชำระกับบริษัท</option>
                                    </select>
                                </div>
                                <div class="col-md-5 mb-4">
                                    <label>ข้อมูลค่าเช่ารถตั้งแต่วันที่เริ่ม - ถึงวันที่</label>
                                    <input type="text" class="form-control required Time" data-count="0" id="CompCar_Start_End_Date"  name="CompCar_Start_End_Date" placeholder="วันที่เริ่ม" >
                                </div>
                            
                                <div class="col-md-12 mb-4">
                                    <label>หมายเหตุ(ข้อมูลค่าเช่ารถ)</label>
                                    <input type="text" class="form-control "  name="CompCar_Remark" placeholder="หมายเหตุ" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="type_save" id="type_save">
                    <button class="btn btn-primary mt-1" type="submit">บันทึกข้อมูล</button>
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

    $("input[name='Year'],input[name='CompInstallment'],input[name='CarInstallment']").mask('0000');

    function formatDate(inputDate) {
        // Parse the input date string
        var date = new Date(inputDate);

        // Extract the day, month, and year
        var day = date.getDate();
        var month = date.getMonth() + 1; // Months are zero-based, so we add 1
        var year = date.getFullYear();

        // Format the date as "MM/DD/YYYY"
        var formattedDate =  day + '/' + month + '/' + year;

        return formattedDate;
    }

    
    $("select[name='EmpDriv']").select2({
        placeholder: "ชื่อพนักงาน",
        dropdownParent: $('#AddGroup'),
        allowClear: true
    });

    $('#Add').click(function (e) { 
        e.preventDefault();
        $('#AddGroup').modal('show');
        $('#type_save').val('0');
        $("#FormCarDriv").trigger('reset');
    });

    $('.Time').daterangepicker({
        // startDate: minDate, // set the initial start date
        // minDate: minDate, // set the initial end date to today
        // maxDate : moment(),
        // timePicker: true, // enable time picker
        timePicker24Hour: true, // use 24-hour time format
        // timePickerIncrement: 15, // increment time by 15 minutes
        opens: 'left', // position the picker to the left of the input
        // singleDatePicker: true,
        locale: {
            format: 'DD/MM/YYYY', // set the format of the selected date range
        }
    });

    $("input[name='RegistDate']").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY', // set the format of the selected date range
        }
    });

    $('.edit').click(function (e) { 
        e.preventDefault();
        let VehicleCode = $(this).data('id');
        $.ajax({
            type: "get",
            url: url+"/EmpDrivGetData/"+VehicleCode,
            // data: {},
            // dataType: "dataType",
            success: function (response) {
                console.log(response);
                $("input[name='VehicleCode']").val(response.VehicleCode);
                $("select[name='CarTypeCode']").val(response.CarTypeCode+":"+response.CarTypeName);
                $("input[name='ProvinceID']").val(response.ProvinceID+":"+response.ProvinceName);
                $("select[name='CarBrandCode']").val(response.CarBrandCode+":"+response.CarBrandName).trigger( "change" );
                $("select[name='CarBrandCode']").attr('data-count',0);
                $("select[name='CarSerieCode']").val(response.CarSerieCode);
                $("input[name='RegistDate']").val(formatDate(response.RegistDate));
                $("input[name='RegistDate']").attr('data-count',0);
                $("input[name='Year']").val(response.Year);
                $("input[name='Color']").val(response.Color);
                $("input[name='VehicleNo']").val(response.VehicleNo);
                $("input[name='BodyNo']").val(response.BodyNo);
                $("select[name='OilTypeCode']").val(response.OilTypeCode+":"+response.OilTypeName);
                $("select[name='FormulaCode']").val(response.FormulaCode+":"+response.FormulaDetail);
                $("input[name='GPS']").val(response.CarGPS);
                $("input[name='Remark']").val(response.Remark);

                let txtCarComp
                switch (response.CarComp) {
                    case 'Y':
                        txtCarComp = "รถบริษัท";
                        break;
                    case 'N':
                        txtCarComp = "รถร่วมบริษัท";
                        break;
                    case 'P':
                        txtCarComp = "รถร่วมส่วนบุคคล";
                        break;
                    case 'C':
                        txtCarComp = "รถร่วมนิติบุคคล";
                        break;
                }
                $("select[name='CarComp']").val(response.CarComp+":"+txtCarComp);
                $("select[name='CoFormulaCode']").val(response.coFormulaCode+":"+response.coFormulaDetail);
                $("input[name='CoTel']").val(response.CoTel);
                $("input[name='CoEmpName']").val(response.CoEmpName);
                $("select[name='EmpDriv']").val(response.EmpDriverCode);
                $("select[name='EmpDriv']").trigger( "change" );
                $("select[name='EmpDriv']").attr('data-count',0);


                $("input[name='PolicyInsureNo']").val(response.PolicyInsureNo);
                $("select[name='InsureTypeCode']").val(response.InsureTypeCode+":"+response.InsureTypeName);
                $("select[name='InsureCompCode']").val(response.InsureCompCode+":"+response.InsureCompName);
                $("input[name='InsureCap']").val(response.InsureCap);
                $("input[name='InsureValue']").val(response.InsureValue);

            
                let InsureStart = moment(response.CarInsureDateStart);
                let InsureEnd   = moment(response.CarInsureDateEnd);
                $("input[name='InsureStart_End_Date']").daterangepicker({ startDate: InsureStart, endDate: InsureEnd });
                $("input[name='InsureStart_End_Date']").attr('data-count',0);

                $("input[name='FormulateNO']").val(response.FormulateNO);
                $("select[name='InsureTypeCode_Form']").val(response.lmCarInsureTypeCode);
                $("select[name='InsureCompCode_Form']").val(response.lmCarInsureCompCode);
                $("input[name='FormulateValue']").val(response.FormulateValue);

                let Insure_Form_Start = moment(response.CarFormDateStart);
                let Insure_Form_End   = moment(response.CarFormDateEnd);
                $("input[name='Insure_Form_Start_End_Date']").daterangepicker({ startDate: Insure_Form_Start, endDate: Insure_Form_End });
                $("input[name='Insure_Form_Start_End_Date']").attr('data-count',0);
    

                $("select[name='FinanceCode']").val(response.FinanceCode+":"+response.FinanceName);
                $("input[name='ContractNo']").val(response.ContractNo);
                $("input[name='CarValue']").val(response.CarValue);
                $("input[name='CarTotalValue']").val(response.CarTotalValue);
                $("input[name='CarDownValue']").val(response.CarDownValue);
                $("input[name='CarInterest']").val(response.CarInterest);
                $("input[name='CarLoanValue']").val(response.CarLoanValue);
                $("input[name='CarInstallmentValue']").val(response.CarInstallmentValue);
                $("input[name='CarInstallment']").val(response.CarInstallment);
                $("input[name='CarInstallmentLeft']").val(response.CarInstallmentLeft);
                $("input[name='CarBalanceValue']").val(response.CarBalanceValue);
                $("select[name='CarFinStatus']").val(response.CarFinStatus);
                $("input[name='CarFinRemark']").val(response.CarFinRemark);

                let CarFin_Start = moment(response.CarFinDateStart);
                let CarFin_End   = moment(response.CarFinDateEnd);
                $("input[name='CarFin_Start_End_Date']").daterangepicker({ startDate: CarFin_Start, endDate: CarFin_End });
                $("input[name='CarFin_Start_End_Date']").attr('data-count',0);
                
                $("input[name='CompCarValue']").val(response.CompCarValue);
                $("input[name='CompCarTotal']").val(response.CompCarTotal);
                $("input[name='CompDownValue']").val(response.CompDownValue);
                $("input[name='CompCarInterest']").val(response.CompCarInterest);
                $("input[name='CompCarLoan']").val(response.CompCarLoan);
                $("input[name='CompInstallmentValue']").val(response.CompInstallmentValue);
                $("input[name='CompInstallment']").val(response.CompCarInstallment);
                $("input[name='CompInstallmentLeft']").val(response.CompInstallmentLeft);
                $("input[name='CompCarBalance']").val(response.CompCarBalance);
                $("input[name='CarCompStatus']").val(response.CarCompStatus);
                $("input[name='CompCar_Remark']").val(response.CarCompRemark);

                let CompCar_Start = moment(response.CarCompDateStart);
                let CompCar_End   = moment(response.CarCompDateEnd);
                $("input[name='CompCar_Start_End_Date']").daterangepicker({ startDate: CompCar_Start, endDate: CompCar_End });
                $("input[name='CompCar_Start_End_Date']").attr('data-count',0);
            
                $('#type_save').val('1');
                $('#AddGroup').modal('show');
            }
        });
    });

    $("#searchInput").on("input", function() {
            // Get the value of the input
        var searchText = $(this).val().toLowerCase();
        
        // Filter the table rows based on the input
        $("#DataEmpDriv tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
        });
    });


    var formData = []; // สร้างตัวแปรเพื่อเก็บข้อมูลที่มีการแก้ไข
    var CheckInput = ['CarBrandCode','EmpDriv','RegistDate','InsureStart_End_Date','Insure_Form_Start_End_Date','CarFin_Start_End_Date','CompCar_Start_End_Date'];
    // var countEdit;
    // ใช้ .on("input") เพื่อตรวจสอบการเปลี่ยนแปลงของ input ทุกครั้งที่พิมพ์
    $("#FormCarDriv input, #FormCarDriv select").on("input change", function() {
        var name        = $(this).attr("name");
        var value       = $(this).val();
        var textLabel   = $(this).prev().text();
        let count       = parseInt($(this).data('count'));

        if (CheckInput.includes(name)) {
            count++; 
            $('#'+name).data('count',count);
        }

        if(!CheckInput.includes(name) || count >= 2){
            formData[name] = {
                                'text' : textLabel,
                                'val' : value,
                                'name' : name
                            }
            // CheckInput.push(name);
        }
        // เก็บข้อมูลที่มีการแก้ไขลงใน formData
    });


    $("select[name='CarBrandCode']").change(function (e) { 
        e.preventDefault();
        let val = $(this).val();
        $.ajax({
            type: "post",
            url: url+"/GetSerieFromBrand",
            data: {'BrandCode':val},
            beforeSend:function(){ 
                $("select[name='CarSerieCode']").empty();
                $("select[name='CarSerieCode']").attr('readonly',true);
            },
            success: function (response) {
                $("select[name='CarSerieCode']").attr('readonly',false);
                let htmlOption;
                $.each(response, function (index, value) {   
                    htmlOption += "<option value=\""+value.CarSerieCode+":"+value.CarSerieName+"\" >"+value.CarSerieCode+" : "+value.CarSerieName+"</option>";
                });
                $("select[name='CarSerieCode']").append(htmlOption);           
            }
        });
    });

    $('#FormCarDriv').submit(function (e) { 
        e.preventDefault();

        let required        = $('.required');
        let required_status = true;
        let type_save       = $('#type_save').val();

        // $.each(required, function(key,val) {             
        //     let input = $(this);
        //     if(input.val() == ""){
        //         let textAlert = input.prev().text();
        //         swal({
        //             title: 'กรุณาระบุข้อมูล',
        //             text: 'ระบุ'+textAlert,
        //             type: 'warning',
        //             padding: '2em'
        //         }).then((result) => {
        //             input.focus();
        //         });
        //         required_status = false;
        //         return false;
        //     }    
        // }); 

        if(required_status){
            var DataForm =  $(this).serialize();

            if (type_save == '1'){

                let VehicleCode = $("input[name='VehicleCode']").val();
            
                DataForm = Object.assign({},{
                    'DataEdit' : Object.assign({}, formData),
                    'type_save' : type_save,
                    'VehicleCode' : VehicleCode
                });
            }
            // console.log(DataForm.DataEdit);
        
            $.ajax({
                type: "post",
                url: url+"/DataEmpDrivSave",
                data: DataForm,
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
                    }else if(response == 'added'){
                        swal({
                            title: 'ไม่สามารถทำรายการได้',
                            text: 'เนื่องจากยังไม่มีการอนุมัติรายการขอแก้ไขก่อนหน้า',
                            type: 'error',
                            padding: '2em'
                        }).then((result) => {
                            location.reload();
                        });
                    }else if(response == 'dataNull'){
                        swal({
                            title: 'ไม่สามารถทำรายการได้',
                            text: 'ไม่พบข้อมูลแก้ไข',
                            type: 'error',
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

    $('.change_st_flag').change(function(e){

    });
</script>
@endsection