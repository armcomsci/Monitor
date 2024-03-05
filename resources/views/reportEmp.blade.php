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
    .select2-container--default.select2-container--focus .select2-selection--multiple{
        overflow: auto;
        max-height: 70px;
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
                                <form id="FindEmpRun" onSubmit="return false">
                                    <div class="form-group row mt-4">
                                        <div class="col-2">
                                            <input type="text" class="form-control" name="dateRange" id="Time" placeholder="ช่วงเวลา">
                                        </div>
                                        <div class="col-3">
                                            <select class="form-control empcode" name="empcode[]" multiple>
                                                <option></option>
                                                @foreach ($EmpName as $emp)
                                                    @php
                                                        $Carsize = '';
                                                        switch ($emp->CarTypeCode) {
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
                                                    <option value="{{ $emp->EmpDriverCode }}">{{ $emp->EmpDriverName }} ({{ $Carsize }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control cargroup" name="cargroup[]" multiple>
                                                <option></option>
                                                @foreach ($CarGroup as $car)
                                                    <option value="{{ $car->EmpGroupCode }}">{{ $car->EmpGroupName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control carSize" name="carSize[]" multiple>
                                                <option></option>
                                                <option value="CT001">รถเล็ก</option>
                                                <option value="CT002">รถกลาง</option>
                                                <option value="CT003">รถใหญ่</option>
                                            </select>
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="button" class="btn btn-outline-primary" id="Find"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="loaddingModal" style="height: 500px;"></div>
                                <div id="dataEmpRun">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ShowDetailRun" tabindex="-1" role="dialog" aria-labelledby="ShowDetailRun" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ข้อมูลร้านค้าที่จัดส่ง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="overflow: scroll; height: 750px;"> 
                <table class="table table-bordered table-hover table-striped mb-4" id="dataCustSend" style="height: auto;">
                    <thead style="background-color:gold;">
                        <th>เลขตู้</th>
                        <th>สินค้า/รหัสสินค้า</th>
                        <th>จำนวน</th>
                        <th>หน่วย</th>
                        <th>ชื่อร้าน</th>
                        <th class="">สถานะ</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
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
    moment.locale('th');

    $(".empcode").select2({
        // tags: true,
        placeholder: "ค้นหาพนักงาน",
    });
    $(".carSize").select2({
        // tags: true,
        placeholder: "ขนาดรถ",
    });
    $('.cargroup').select2({
        // tags: true,
        placeholder: "กลุ่มรถ",
    });


    const minDate = new Date();
    minDate.setMonth(minDate.getMonth() - 3);
    minDate.setDate(1)

    $(document).ready(function () {
        $('#Time').daterangepicker({
            startDate: minDate, // set the initial start date
            minDate: minDate, // set the initial end date to today
            maxDate : moment(),
            // timePicker: true, // enable time picker
            timePicker24Hour: true, // use 24-hour time format
            // timePickerIncrement: 15, // increment time by 15 minutes
            opens: 'left', // position the picker to the left of the input
            // singleDatePicker: true,
            locale: {
                format: 'DD/MM/YYYY', // set the format of the selected date range
            }
        });

        $('#Find').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "post",
                url: url+"/FindEmpRun",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: $('#FindEmpRun').serialize(),
                beforeSend:function(){
                    $('.loaddingModal').css('display','block');
                    $('#dataEmpRun').empty();
                },
                success: function (response) {
                    $('.loaddingModal').css('display','none');
                    $('#dataEmpRun').html(response);
                    $('#Table-EmpRun,#SumRunEmp').DataTable( {
                        "dom": "<'dt--top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                        buttons: {
                            buttons: [
                              
                                { extend: 'excel', className: 'btn btn-sm' },
    
                            ]
                        },
                        "oLanguage": {
                            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                            "sInfo": "Showing page _PAGE_ of _PAGES_",
                            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                            "sSearchPlaceholder": "Search...",
                        "sLengthMenu": "Results :  _MENU_",
                        },
                        "stripeClasses": [],
                        "lengthMenu": [7, 10, 20, 50],
                        "pageLength": 7 ,
                        "ordering": true
                    });
                    $("#RangeDateSum").text($('#Time').val());

                  
                }
            });
        });
    });
    
    $(document).on('click','.DetailRun',function(e){
        let stampDate = $(this).data('stampdate');
        let empcode   = $(this).data('empcode');
        
        $('.DetailRun').removeClass('activeTr');
        $(this).addClass('activeTr');

        $.ajax({
                type: "post",
                url: url+"/DetailStampDate",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'stampDate':stampDate,'empcode':empcode},
                beforeSend:function(){
                    $('#ShowDetailRun').modal('show');
                    $('#dataCustSend tbody').empty();
                },
                success: function (response) {
                    let html = '';
                    if(response.length != 0){
                        $.each(response, function (index, value) { 
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
                            html += "<td>"+value.ContainerNO+"</td>"
                            html += "<td class=\"text-break\">"+value.GoodName+"<br>"+value.GoodCode+"</td>"
                            html += "<td>"+value.GoodQty+"</td>"
                            html += "<td>"+value.GoodUnit+"</td>"
                            html += "<td class=\"text-break\">"+value.CustName+"</td>"
                            html += "<td><span class=\""+bg_class+"\">"+bg_text+"</span></td>"
                            html += "</tr>"
                            i++;
                        });
                    }else{
                        html = "<tr><td colspan=\"6\" class=\"text-center\">ไม่พบข้อมูล</td></tr>"
                    }
                 
                    $('#dataCustSend tbody').append(html);
                    
                }
        });
        
    });

</script>
@endsection