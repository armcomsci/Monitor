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
 <style>
    .table-orver-flow{
        height: 600px;
        overflow: scroll;
        overflow-x: hidden;
    }
    .table-orver-flow thead th { 
        position: sticky; top: 0; z-index: 1; 
    }
    .Driv{
        display: none;
    }
    i{
        margin-right: 5px;
        margin-left: 5px;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>กำหนดคนรถ</span></li>
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
                                <form id="EmpDrive" style="margin-top: 20px;">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">กำหนดส่ง</span>
                                                </div>
                                                <input type="text" name="workdate" id="Work_date"  class="form-control flatpickr flatpickr-input active">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend" style="margin-right: 0px;">
                                                    <label class="input-group-text" >ขนาดรถ</label>
                                                </div>
                                                <select name="car_size" class="custom-select" id="CarSize" style="background-color: #ffffff">
                                                    <option value=""></option>
                                                    @foreach ($Cardriv as $item)
                                                        <option value="{{ $item->TranspID }}">{{ $item->TranspName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" id="Filter_emp" class="btn btn-info">ค้นหา</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-6 Driv">
                                        <div class="card" style="height: 650px;">
                                            <div class="card-header">
                                                <h5>รายชื่อคนรถ</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-3" style="padding: 0px; margin-bottom: 15px;">
                                                    <input type="text" name="" id="input_1" value="" class="form-control">
                                                </div>
                                                <div class="table-responsive table-orver-flow">
                                                    <form id="SaveEmpWork" method="post">
                                                        <table class="table table-hover list_emp">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <div class="checkbox checkbox-danger checkbox-fill d-inline">
                                                                            <input type="checkbox" name="Check_all" id="Check_all" >
                                                                            <label for="Check_all" class="cr"></label>
                                                                        </div>
                                                                    </th>
                                                                    <th>ชื่อ-นามสกลุ</th>
                                                                    <th>ทะเบียนรถ</th>   
                                                                    <th>ขนาดรถ</th>                                        
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 Driv">
                                        <form id="FormSaveWork">
                                            <div class="card " style="height: 650px;">
                                                <div class="card-header" style="display: flex">
                                                        <h5 style="width: 100px;padding-top: 10px;">กำหนดส่ง </h5>
                                                        <input type="text" name="DateWork" readonly class="form-control-plaintext" id="Date_text" value=""> 
                                                </div>
                                                <div class="card-body row">
                                                    <div class="col-md-3" style="padding: 0px; margin-bottom: 15px; padding-left: 15px;">
                                                        จำนวนพนักงานที่เพิ่ม : <span id="count_add">0</span>
                                                    </div>
                                                    <div class="offset-md-7 col-md-2" style="padding: 0px; margin-bottom: 15px;">
                                                        <button type="button" class="btn  btn-info" id="save_work"><i class="fas fa-save"></i> บันทึก</button>
                                                    </div>
                                                    <div class="table-responsive table-orver-flow" style="height: 600px;">
                                                            @csrf
                                                            <table class="table list_select">
                                                                <thead>
                                                                    <tr>
                                                                       
                                                                        <th>ชื่อ-นามสกลุ</th>
                                                                        <th>ทะเบียนรถ</th> 
                                                                        <th>ขนาดรถ</th>
                                                                        <th>สถานะ</th> 
                                                                        <th>ลบ</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    
                                                                </tbody>
                                                            </table>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
<script src="{{ asset('theme/assets/js/daterangepicker.js') }}"></script>
<script>

    function InputAlert(){
        let leave = {!! $leave !!}

        let html  = ' <div class="form-row"> <div class="col-md-6 mb-4"><select  class="form-control" name="leave_type">';
        $.each( leave, function( key, value ) {
            html += '<option value="'+value['id']+'">'+value['leave_name']+'</option>';
        });
        html += '</select></div><div class="col-md-3 mb-4"><input type="number" class="form-control" placeholder="จำนวน" name="leave_amount" min="1" required /></div> <div class="col-md-3 mb-4"><select class="form-control" name="leave_day"><option value="H">ชั่วโมง</option><option value="D" >วัน</option></select></div><div class="col-md-12 mb-4"><input type="text" class="form-control" name="leave_remark" placeholder="หมายเหตุ" required /></div> </div>';
        return html;
    }

    function getLastWeeksDate() {
        const now = new Date();

        return new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate() - 7,
        );
    }

    $('#Work_date').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "minDate": getLastWeeksDate(),
        // "autoUpdateInput": false,
        "autoApply":true,
        locale: {
            format: 'DD-MM-YYYY',
            // cancelLabel: 'Clear'
        }
    });

    $(document).ready(function(){
        $("#input_1").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".list_emp tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('#Filter_emp').click(function (e) { 
            e.preventDefault();
            let CarSize     = $('#CarSize').val();
            let Car_Text    = $('#CarSize option:selected').text();
            if(CarSize == ""){
                Swal.fire("ไม่สามารถค้นหาได้", "กรุณาเลือกขนาดรถ", "error");
                return false;
            }else{
                $.ajax({
                    type: "post",
                    url: url+"/FilterEmp",
                    data: $('#EmpDrive').serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $("#Filter_emp").attr('disabled',true);
                        $('#Filter_emp').empty();
                        $('#Filter_emp').append('<span class="spinner-border spinner-border-sm"></span> Loading...');
                    },
                    success: function (response) {
                        $("#Filter_emp").attr('disabled',false);
                        $("#Filter_emp").empty();
                        $("#Filter_emp").append('ค้นหา');

                        let Work_date = $('#Work_date').val();
                        $('.Driv').css('display','block');
                        $('#Date_text').val(Work_date);
                        
                        let html_emp = "";
                        let a = 0;
                        let All = [];
                        $.each( response.Emp, function( key, value ) {
                                html_emp += "<tr id=\"AllEmp_"+value.EmpDriverCode+"\">";
                                html_emp += "<td><div class=\"checkbox checkbox-danger checkbox-fill d-inline\"><input type=\"checkbox\" name=\"DriverCode[]\" class=\"Select_emp\" id=\"Code-"+value.EmpDriverCode+"\" value=\""+value.EmpDriverCode+"\" ><label for=\"Code-"+value.EmpDriverCode+"\" class=\"cr\"></label></div></td>";
                                html_emp += "<td id=\"Empname-"+value.EmpDriverCode+"\">"+value.EmpDriverName+"</td>";
                                html_emp += "<td id=\"Vehiclecode-"+value.EmpDriverCode+"\">"+value.VehicleCode+"</td>";
                                html_emp += "<td id=\"Car-"+value.EmpDriverCode+"\">"+Car_Text+"</td>"
                                html_emp += "</tr>";
                                All.push(value.EmpDriverCode);
                        });
                        $('.list_emp tbody').empty();
                        $('.list_emp tbody').append(html_emp);

                        // console.log(response);
                        let html_selectd = "";
                        let checked;
                        if(response.Send != ""){
                            $.each(response.Send, function ( key, value) { 
                                if(All.indexOf(value.EmpDriverCode) >= -1){
                                    $('#AllEmp_'+value.EmpDriverCode).remove();
                                }
                                checked = "";
                                html_selectd += "<tr id=\"Emp_"+value.EmpDriverCode+"\">";
                                html_selectd += "<td>"+value.EmpDriverName+"</td>";
                                html_selectd += "<td>"+value.VehicleCode+"</td>";
                                html_selectd += "<td>"+Car_Text+"</td>";
                                if(value.Status == "Y"){
                                    checked = "checked";
                                }
                                html_selectd += "<td><div class=\"switch switch-success d-inline m-r-10\"><input type=\"checkbox\" id=\"status-"+value.EmpDriverCode+"\" class=\"Status_used\" "+checked+" value=\""+value.EmpDriverCode+"\"><label for=\"status-"+value.EmpDriverCode+"\" class=\"cr\"></label></div></td>";
                                html_selectd += "<td></td>";
                                // html_selectd += "<input type=\"hidden\" value=\""+val+"\" name=\"Empcode[]\" >";
                                html_selectd += "</tr>";
                            });
                        }
                        $('.list_select tbody').empty();
                        $('.list_select tbody').append(html_selectd);

                        let count_row   = $('.list_select tbody tr').length;
                        $('#count_add').html(count_row)
                    },
                    error: function (error) {
                        $(this).click();
                    }
                });
            }
        });

        $('#save_work').click(function (e) { 
            e.preventDefault();
            if(!$('.Select_emp').is(':checked')){
                swal({
                    title: 'ไม่สามารถบันทึกได้',
                    text: "กรุณาเลือกคนส่ง",
                    icon: 'warning',
                })
                return false;
            }
            swal({
                title: 'ต้องการบันทึกข้อมูล ?',
                text: "หากบันทึกข้อมูลแล้วไม่สามารถลบพนักงานออกได้ !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: url+"/SaveWorkDate",
                        data: $('#FormSaveWork').serialize(),
                        // dataType: "json",
                        success: function (response) {
                            // console.log(response);
                            // $('.remove_emp').remove();
                            // $.each('.Status_used', function( key, value ) {
                            $("#Filter_emp").click();
                            // });
                        },
                        error: function (error) {
                           $(this).click();
                        }
                    });
                }
            })
        });
    });

    $(document).on('change','.Status_used',function(){
        let Empcode     = $(this).val();
        let WorkDate    = $('#Work_date').val();
        let checkBoxes = $(this);
        let status;
        if($(this).is(':checked')){
            status = "Y";
            Swal.fire({
                title: 'ต้องการเปลี่ยนสถานะ ?',
                icon: 'info',
                showCancelButton: true,
                // confirmButtonColor: '#3085d6',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ไม่ต้องการ',
            }).then(function(result) {
                if (result.value) {
                   
                    $.ajax({
                        type: "post",
                        url: url+"/ChangeStatusEmp",
                        data: { 'Empcode':Empcode,
                                'Status':status,
                                'DateWork':WorkDate
                            },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        // dataType: "dataType",
                        success: function (response) {
                            if(response == "success"){
                                Swal.fire("แก้ไข้ข้อมูลสำเร็จ", "", "success");
                            }else{
                                Swal.fire("เกิดข้อผิดพลาดในการบันทึก", "กรุณาแก้ไขอีกครั้ง", "error");
                            }
                            
                        }
                    });
                }else{
                    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
                }
            });
        }else{
            status = "N";

            Swal.fire({
                title: 'ต้องการเปลี่ยนยกเลิกคนขับ ?',
                html: InputAlert(),
                icon: 'info',
                showCancelButton: true,
                // confirmButtonColor: '#3085d6',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ไม่ต้องการ',
                preConfirm: () => {
                    // Get input and select values
                    const leave_type        = Swal.getPopup().querySelector("select[name='leave_type']").value;
                    const leave_amount      = Swal.getPopup().querySelector("input[name='leave_amount']").value;
                    const leave_day         = Swal.getPopup().querySelector("select[name='leave_day']").value;
                    const leave_remark      = Swal.getPopup().querySelector("input[name='leave_remark']").value;
                    
                    if(!leave_amount){
                        alert('ระบุจำนวนวันลา')
                        return false;
                    }
                    if(!leave_remark){
                        alert('ระบุหมายเหตุการลา')
                        return false;
                    }
                
                    return { type: leave_type, amount: leave_amount, day: leave_day, remark: leave_remark };
                    
                }
            }).then(function(result) {
                if (result.value) {
                    const { type, amount,day,remark } = result.value;

                    $.ajax({
                        type: "post",
                        url: url+"/ChangeStatusEmp",
                        data: { 'Empcode':Empcode,
                                'Status':status,
                                'DateWork':WorkDate,
                                'type':type,
                                'amount':amount,
                                'day':day,
                                'remark':remark,
                            },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        // dataType: "dataType",
                        success: function (response) {
                            if(response == "success"){
                                Swal.fire("แก้ไข้ข้อมูลสำเร็จ", "", "success");
                            }else{
                                Swal.fire("เกิดข้อผิดพลาดในการบันทึก", "กรุณาแก้ไขอีกครั้ง", "error");
                            }
                            
                        }
                    });
                }else{
                    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
                }
            });
        }
    });
    $(document).on('click','#Check_all',function(){
        $( ".Select_emp" ).each(function( index ) {
            $(this).click();
        });
    });
    $(document).on('click','.Select_emp',function(){
        let val = $(this).val();
        let empname = $('#Empname-'+val).html();
        let vehiclecode = $('#Vehiclecode-'+val).html();
        let car = $('#Car-'+val).html();
        
        if($(this).is(':checked')){
            if($("#Emp_"+val).length > 0){
                Swal.fire("ไม่สามารถเพิ่มคนขับได้", "ข้อมูลดังกล่าวถูกเพิ่มแล้ว", "error");
                return false;
            }else{
                html_select  = "<tr id=\"Emp_"+val+"\">";
                // html_select += "<td>"+count_row+"</td>";
                html_select += "<td>"+empname+"</td>";
                html_select += "<td>"+vehiclecode+"</td>";
                html_select += "<td>"+car+"</td>";
                html_select += "<td></td>";
                html_select += "<td><button type=\"button\" class=\"btn  btn-outline-danger remove_emp\" data-empcode=\""+val+"\"><i class=\"fas fa-trash-alt\"></i></button></td>";
                html_select += "<input type=\"hidden\" value=\""+val+"\" name=\"Empcode[]\" >";
                html_select += "</tr>";
                
                $('.list_select tbody ').prepend(html_select);
            }
            
        }else{
            $('#Emp_'+val).remove();
        }
        let count_row   = $('.list_select tbody tr').length;
        $('#count_add').html(count_row)
    });
    $(document).on('click','.remove_emp',function(){
        let code = $(this).data('empcode');
        swal({
            title: 'ต้องการลบ ?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            // confirmButtonColor: '#3085d6',
            // cancelButtonColor: '#d33',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then(function(result) {
            if (result.value) {

                $('#Emp_'+code).remove();
                $('#Code-'+code).prop('checked',false);

                let count_row   = $('.list_select tbody tr').length;
                $('#count_add').html(count_row)
            }
        })
    });
</script>
@endsection