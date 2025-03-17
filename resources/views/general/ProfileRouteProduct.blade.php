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
    .loaddingModal{
        background-image: url("{{ asset('icon/truck.gif') }}");
        background-position: center;
        background-repeat: no-repeat;
        height: 100%;
        display: none;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>กำหนดข้อมูลปลายทางขนส่งสินค้า</span></li>
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
                                <form id="FindRoute" action="javascript:void(0);">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-4">
                                            <label>รหัสปลายทาง</label>
                                            <select class="form-control required" name="AreaCode">
                                                <option value=""></option>
                                                @foreach ($AreaZone as $item)
                                                    <option value="{{ $item->AreaCode }}">{{ $item->AreaCode.' : '.$item->AreaName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4" style="padding-top:34px;"> 
                                            <button class="btn btn-primary" type="submit">ค้นหา</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="DataArea">
                                    <div class="loaddingModal" style="height: 500px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DataMarket" tabindex="-1" role="dialog" aria-labelledby="DataMarket" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ข้อมูลเส้นทาง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 770px;"> 
                <form id="SaveMarketZone" action="javascript:void(0);">
                    <div class="table-responsive" style="height: 700px;">
                        <div class="col-3 mb-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search">
                        </div>
                        
                            <table class="table table-bordered mb-4" id="MarketTable">
                                <thead style="background: #ddeb60">
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="Check_all_market">                              
                                        </th>
                                        <th>ชื่อโซน</th>
                                        <th>หมายเหตุ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lmBkkMart_tm as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="MarketID[]" value="{{ $item->MarketID }}">
                                            </td>
                                            <td>{{ $item->MarketName }}</td>
                                            <td>{{ $item->Remark }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                    <input type="hidden" id="AreaCode" name="AreaCode">
                    <button class="btn btn-primary mt-2" type="submit">บันทึกข้อมูล</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DataTrans" tabindex="-1" role="dialog" aria-labelledby="DataTrans" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ข้อมูลเส้นทาง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 770px;"> 
                
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
    
    $("select[name='AreaCode']").select2({
        placeholder: "รหัสปลายทาง",
        allowClear: true
    });


    $('#FindRoute').submit(function (e) { 
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
                url: url+"/ProfileRouteProductData",
                data: $(this).serialize(),
                beforeSend:function(){
                    $('#DataArea').empty();
                    $('.loaddingModal').css('display','block');
                },
                success: function (response) {
                    $('.loaddingModal').css('display','none');
                    $('#DataArea').html(response);
                    let AreaCode =  $( "select[name='AreaCode']" ).val();
                    $('#AreaCode').val(AreaCode);
                }
            });
        }
       
    });

    $("#searchInput").on("input", function() {
            // Get the value of the input
        var searchText = $(this).val().toLowerCase();
        $(".highlight").removeClass("highlight");
        
        // Filter the table rows based on the input
        $("#MarketTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
        });
    });
    $(document).on('input','#searchInput_trans',function (e) { 
        let searchText = $(this).val().toLowerCase();
        $(".highlight").removeClass("highlight");
        
        // Filter the table rows based on the input
        $("#TransTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
        });
    });

    

    $(document).on('change','.Check_all_market', function (e) { 
        e.preventDefault();
        $("input[name='MarketID[]']").not(this).prop('checked', this.checked);
    });
    
    $(document).on('submit','#SaveMarketZone', function (e) {  
        let MarketID =  $('input[name="MarketID[]"]:checked').map(function () {
                                    return this.value;
                                }).get();

        if(MarketID.length == 0){
            swal({
                title: 'กรุณาเลือกรายการ',
                text: '',
                type: 'error',
                padding: '2em'
            })
        }else{
            $.each(MarketID, function (index, value) { 
                if($('#marketZone tbody tr').hasClass('market-'+value)){
                    swal({
                        title: 'รายการที่เลือกมีอยู่แล้ว',
                        text: '',
                        type: 'error',
                        padding: '2em'
                    });
                    return false;
                }
            });
            $.ajax({
                type: "post",
                url: url+"/ProfileRouteProductSave",
                data: $(this).serialize(),
                beforeSend:function(){
                    // $('#DataArea').empty();
                    // $('.loaddingModal').css('display','block');
                },
                success: function (response) {
                    if(response == 'success'){
                        $('#DataMarket').modal('hide');
                        $('#FindRoute').submit();
                    } 
                }
            });
        }
    });
    
    $(document).on('click','#AddMarket', function (e) { 
        e.preventDefault();
        $('#DataMarket').modal('show');
    });
    
    $(document).on('click','#AddTrans',function(e){
        e.preventDefault();
        let AreaCode = $("select[name='AreaCode']").val();
        $.ajax({
            type: "get",
            url: url+"/GetlmCenTran",
            data: {'AreaCode':AreaCode},
            // dataType: "dataType",
            success: function (response) {
                $('#DataTrans').find('.modal-body').html(response);
                $('#DataTrans').modal('show');
            }
        });
    });

    $(document).on('submit','#SaveTranCenZone', function (e) { 
        let TranCenID =  $('input[name="TranCenID[]"]:checked').map(function () {
                                    return this.value;
                                }).get();

        if(TranCenID.length == 0){
            swal({
                title: 'กรุณาเลือกรายการ',
                text: '',
                type: 'error',
                padding: '2em'
            })
            return;
        }

        $.ajax({
            type: "post",
            url: url+"/ProfileRouteTransSave",
            data: $(this).serialize(),
            beforeSend:function(){
                // $('#DataArea').empty();
                // $('.loaddingModal').css('display','block');
            },
            success: function (response) {
                if(response == 'success'){
                    $('#DataTrans').modal('hide');
                    $('#FindRoute').submit();
                } 
            }
        });
    });
</script>
@endsection