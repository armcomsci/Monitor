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
                            <li class="breadcrumb-item active" aria-current="page"><span>ข้อมูลเส้นทาง กทม.</span></li>
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
                                        <a class="nav-link active" id="border-top-home-tab" data-toggle="tab" href="#main-zone" role="tab" aria-controls="border-top-home" aria-selected="true">ข้อมูลโซนหลัก</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="SubZone-tap" data-toggle="tab" href="#sub-zone" role="tab" aria-controls="border-top-profile" aria-selected="false">ข้อมูลโซนย่อย</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="BkkMain_tm" data-toggle="tab" href="#main_tm" role="tab" aria-controls="border-top-contact" aria-selected="false">ข้อมูลตลาด</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="Bkkmain_to_subzone" data-toggle="tab" href="#main_to_subzone" role="tab" aria-controls="border-top-contact" aria-selected="false">ข้อมูลโซนย่อยเข้าโซนหลัก</a>
                                    </li>
                                </ul>
                                <div class="tab-content" >
                                    <div class="tab-pane fade show active" id="main-zone" role="tabpanel" aria-labelledby="border-top-home-tab">
                                        <h3 class="mb-4 pt-2">ข้อมูลโซนหลัก</h3>
                                      
                                    </div>
                                    <div class="tab-pane fade" id="sub-zone" role="tabpanel" aria-labelledby="border-top-home-tab">
                                        <h3 class="mb-4 pt-2">ข้อมูลโซนย่อย</h3>
                                        <div class="table-responsive" style="height: 650px;" id="DataSubZone">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="main_tm" role="tabpanel" aria-labelledby="border-top-home-tab">
                                        <h3 class="mb-4 pt-2">ข้อมูลตลาด</h3>
                                        <div class="table-responsive" style="height: 650px;" id="DataMainTm">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="main_to_subzone" role="tabpanel" aria-labelledby="border-top-home-tab">
                                        <h3 class="mb-4 pt-2">ข้อมูลโซนย่อยเข้าโซนหลัก</h3>
                                        <div class="table-responsive" style="height: 650px;" id="DataMainToSub">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="subzone_mart" role="tabpanel" aria-labelledby="border-top-home-tab">
                                        <h3 class="mb-4 pt-2">ข้อมูลการกำหนดตลาดเข้าโซนย่อย</h3>
                                        <div class="table-responsive" style="height: 650px;" id="DataSubZoneMart">
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
    $('#SubZone-tap').click(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "get",
            url: url+"/GetSubZone",
            // data: "data",
            // dataType: "dataType",
            success: function (response) {
                $('#DataSubZone').empty();
                $('#DataSubZone').html(response);
            }
        });
    });

    $('#BkkMain_tm').click(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "get",
            url: url+"/GetMarZone",
            // data: "data",
            // dataType: "dataType",
            success: function (response) {
                $('#DataMainTm').empty();
                $('#DataMainTm').html(response);
            }
        });
    });

    $('#Bkkmain_to_subzone').click(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "get",
            url: url+"/GetMarToSubZone",
            // data: "data",
            // dataType: "dataType",
            success: function (response) {
                $('#DataMainToSub').empty();
                $('#DataMainToSub').html(response);
            }
        });
    });

    // $('#subzone_mart').click(function (e) { 
    //     e.preventDefault();
    //     $.ajax({
    //         type: "get",
    //         url: url+"/GetSubZoneMart",
    //         // data: "data",
    //         // dataType: "dataType",
    //         success: function (response) {
    //             $('#DataMainToSub').empty();
    //             $('#DataMainToSub').html(response);
    //         }
    //     });
    // });
</script>
@endsection