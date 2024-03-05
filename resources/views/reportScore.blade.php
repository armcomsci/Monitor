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
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/table/datatable/datatables.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/table/datatable/dt-global_style.css') }}">
 <style>
    #Table-JobClose tr{
        cursor: pointer;
    }
    .table > tbody > tr > td{
        white-space: unset;
        color:#2c5ccd;
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
                             <li class="breadcrumb-item active" aria-current="page"><span>รายงานคะแนนแต่ละเดือน</span></li>
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
                                <form id="FindScore" onSubmit="return false">
                                    <div class="form-group row mt-4">
                                        <div class="col-3">
                                            <input type="text" class="form-control" name="dateRange" id="Time" placeholder="ช่วงเวลา">
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="button" class="btn btn-outline-primary" id="Find"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="loaddingModal" style="height: 500px;"></div>
                                <div id="dataScoreJob">
                                    
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
<script src="{{ asset('theme/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('theme/plugins/apex/apexcharts.min.js') }}"></script>
<script>
    
    const minDate = new Date();
    minDate.setMonth(minDate.getMonth() - 3);
    minDate.setDate(1)

    $('#Time').daterangepicker({
        startDate: minDate, // set the initial start date
        // minDate: minDate, // set the initial end date to today
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
        $.ajax({
            type: "post",
            url: url+"/FindScore",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $('#FindScore').serialize(),
            beforeSend:function(){
                $('.loaddingModal').css('display','block');
                $('#dataScoreJob').empty();
            },
            success: function (response) {
                $('.loaddingModal').css('display','none');
                $('#dataScoreJob').html(response);
                
            }
        });
    });
</script>
@endsection
