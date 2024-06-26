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
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/select2/select2.min.css') }}">
 <link href="{{ asset('theme/assets/css/elements/avatar.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('theme/assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="{{ asset('theme/assets/css/daterangepicker.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/table/datatable/datatables.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/table/datatable/dt-global_style.css') }}">
 <style>
    #Table-JobClose tr{
        cursor: pointer;
    }
    .table > tbody > tr > td{
        white-space: unset;
    }
    .activeTr{
        background: #e4f852;
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
                             <li class="breadcrumb-item active" aria-current="page"><span>รายงาน งานที่ปิดทั้งหมด</span></li>
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
                                <form id="FindJobClose" onSubmit="return false">
                                    <div class="form-group row mt-4">
                                        <div class="col-3">
                                            <input type="text" class="form-control" name="dateRange" id="Time" placeholder="ช่วงเวลา">
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control port" name="port" >
                                                <option value=""></option>
                                                    @foreach ($Users as $user)
                                                        <option value="{{ $user->EmpCode }}">{{ $user->Fullname }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <select class="form-control cargroup" name="cargroup[]" multiple>
                                                <option value=""></option>
                                                @foreach ($CarGroup as $car)
                                                    <option value="{{ $car->EmpGroupCode }}">{{ $car->EmpGroupName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="button" class="btn btn-outline-primary" id="Find"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="loaddingModal" style="height: 500px;"></div>
                                <div id="dataJobClose">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ShowDetailSend" tabindex="-1" role="dialog" aria-labelledby="ShowDetailSend" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ข้อมูลสินค้า/ร้านค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
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
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('theme/assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('theme/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('theme/plugins/select2/select2.min.js') }}"></script>
<script>
     $('.cargroup').select2({
        // tags: true,
        placeholder: "กลุ่มรถ",
    });
    $('.port').select2({
        // tags: true,
        placeholder: "ผู้ดูแล",
    });

    const minDate = new Date();
    minDate.setMonth(minDate.getMonth() - 3);
    minDate.setDate(1)

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
        let port = $("select[name='port']").val();
        if(port == ''){
            swal({
                title: 'กรุณาระบุผู้ดูแล',
                text: '',
                type: 'warning',
                padding: '2em',
                showConfirmButton: false
            })
            return false;
        }
        $.ajax({
            type: "post",
            url: url+"/FindJobClose",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $('#FindJobClose').serialize(),
            beforeSend:function(){
                $('.loaddingModal').css('display','block');
                $('#dataJobClose').empty();
            },
            success: function (response) {
                $('.loaddingModal').css('display','none');
                $('#dataJobClose').html(response);
                $('#Table-JobClose').dataTable({
                    "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                    "oLanguage": {
                        "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                        "sInfo": "Showing page _PAGE_ of _PAGES_",
                        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                        "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                    },
                    "stripeClasses": [],
                    // "lengthMenu": [7, 10, 20, 50],
                    "pageLength": 7 ,
                    "ordering": true
                });
                $("#SumJobClose").text();
            }
        });
    });

    $(document).on('click','.showDetail',function(e){
        let ContainerNo = $(this).data('containerno');
        
        $('.showDetail').removeClass('activeTr');
        $(this).addClass('activeTr');

        $.ajax({
            type: "get",
            url: url+"/JobCloseOrderItem/"+ContainerNo,
            // data: "data",
            dataType: "json",
            beforeSend:function(){
                $('.ItemOrder tbody tr').remove();
                $('#ShowDetailSend').modal('show');
            },success: function (response) {
                // $('#ShowDetailSend').modal('show');
                let html = '';
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
                    html += "<td class=\"text-break\">"+value.GoodName+"<br>"+value.GoodCode+"</td>"
                    html += "<td>"+value.GoodQty+"</td>"
                    html += "<td>"+value.GoodUnit+"</td>"
                    html += "<td class=\"text-break\">"+value.CustName+"</td>"
                    html += "<td><span class=\""+bg_class+"\">"+bg_text+"</span></td>"
                    html += "</tr>"
                    i++;
                });
                $('.ItemOrder tbody').append(html);
            }
        });
    });
    
</script>
@endsection
