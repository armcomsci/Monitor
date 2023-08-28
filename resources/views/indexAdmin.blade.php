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
 <style>
    thead{
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .Send_Success{
        background-color: #2fa932 !important;
    }
    .activeTr{
        background-color: yellow;
    }
    .successFlag{
        background-color: #77dd77;
    }
    .alertFlag{
        background-color:  #ff6961;
    }
    .loaddingModal{
        background-image: url("{{ asset('icon/truck.gif') }}");
        background-position: center;
        background-repeat: no-repeat;
        height: 100%;
        display: none;
    }
    .dataContain,.CustCode{
        cursor: pointer;
    }
    .avatar{
        width: 64px !important;
        height: 64px !important;
    }
    .hiddenimg {
        display: none;
    }
    .hidden-list:hover ~ .hiddenimg {
        display: block;
        position: absolute;
        z-index: 2;
        left: 100px;
        top: 0px;
        background: #fff;
        border-radius: 10px;
        padding: 5px;
    }
    #map{
        height: 300px;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>ติดตามรถทั้งหมด</span></li>
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
                                <div class="row">
                                    <div class="col-5">
                                        <form id="FindJob">
                                            <div class="form-group row mt-4">
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="findjob" placeholder="เลขตู้/คนรถ/ทะเบียนรถ">
                                                </div>
                                                <div class="col-5">
                                                    <select class="form-control" name="port">
                                                        <option></option>
                                                        @foreach ($Users as $user)
                                                            <option value="{{ $user->EmpCode }}">{{ $user->Fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-1 mt-1">
                                                    <button type="button" class="btn btn-outline-primary" id="FindContain"><i class="fa-solid fa-magnifying-glass"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="dataContain">
                                            <div class="loaddingModal" style="height: 500px;"></div>
                                        </div>
                                    </div>
                                    <div class="col-7" id="JobDetail">
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

<div class="modal fade" id="DataItem" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >รายละเอียดสินค้าในร้าน : <span id="CustName"></span></h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover table-striped mb-4 ItemOrder" style="height: auto;">
                    <thead style="background-color:gold;">
                            <th>รหัสสินค้า</th>
                            <th>สินค้า</th>
                            <th>จำนวน</th>
                            <th>หน่วย</th>
                            <th class="">สถานะ</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DataTimeline" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Header-Timeline"></h5>
            </div>
            <div class="modal-body">
                <div class="loaddingModal"></div>
                <div id="tracking-pre"></div>
                <div id="tracking" style="height: 630px; overflow-x: auto;">
                    <div class="text-center tracking-status-intransit">
                        <p class="tracking-status text-tight">Time Line</p>
                    </div>
                    <div class="tracking-list" style="overflow-x:auto">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var dataApi;
    $(document).ready(function () {
        $('#FindContain').click(function (e) { 
            e.preventDefault();
            let findjob = $("input[name='findjob']").val();
            let port    = $("input[name='port']").val();

            if(findjob == "" && port == ""){
                swal({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณาระบุข้อมูล',
                    type: 'success',
                    padding: '2em'
                });
                return false;
            }else{
                $.ajax({
                    type: "post",
                    url: url+"/FindJobInPort",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: $('#FindJob').serialize(),
                    beforeSend:function(){
                        $('#dataContain > .loaddingModal').css('display','block');
                    },
                    success: function (response) {
                        $('.loaddingModal').css('display','none');
                        $('#dataContain').html(response);
                        $('#JobDetail').empty();
                    }
                });
            }
        });
    });

    $(document).on('click','.dataContain',function(e){
        e.preventDefault();
        let Container = $(this).data('contain');
        $('.dataContain').removeClass('activeTr');
        $(this).addClass('activeTr');
        $.ajax({
            type: "get",
            url: url+"/JobInPortDetail/"+Container,
            // data: "data",
            // dataType: "json",
            beforeSend:function(){
                // showLoading();
                $('#JobDetail').append("<div class=\"loaddingModal\" style=\"height: 600px; width: 100%;\"></div>");
            },
            success: function (response) {
                $('#JobDetail').html(response);
            }
        });
    });

    $(document).on('click','.timeline',function(e){
        $('#DataTimeline').modal('show');
        let Container   = $('.activeTr').data('contain');
        if(Container != ""){
            $.ajax({
                type: "get",
                url: url+"/DtOrderItem/"+Container,
                // data: "data",
                dataType: "json",
                beforeSend:function(){
                    $('.tracking-list').empty();
                    $('.loaddingModal').css('display','block');
                    // $('.loaddingModal').next().css('display','none');
                },
                success: function (response) {
                    $('.loaddingModal').css('display','block');
                    if(response['CustList'] != ""){
                        $('#Header-Timeline').text('Timeline เลขตู้ : '+Container)
                        let html_cust = '';
                        $.each(response['CustList'], function (index, value) { 
                                // console.log(value.Flag_st_date);
                                let statusSend = '';
                                if(value.Flag_st == 'Y'){
                                    statusSend = 'Send_Success';
                                }else{
                                    statusSend = 'Send_not';
                                }
                                html_cust += "<div class=\"tracking-item\">";
                                html_cust += "<div class=\"tracking-icon status-intransit "+statusSend+"\">";
                                // html_cust += "<i class=\"fa fa-circle\"></i>"
                                html_cust += "</div>";
                                if(value.Flag_st_date != null){
                                    html_cust += "<div class=\"tracking-date\">"+moment(value.Flag_st_date).format('D MMMM YYYY')+"<span>"+moment(value.Flag_st_date).format('HH:mm')+"</span></div>";
                                }else{
                                    html_cust += "<div class=\"tracking-date\"></div>";
                                }
                            
                                html_cust += "<div class=\"tracking-content\">"+value.CustName+"<span>"+value.ShiptoAddr1+"</span></div>"
                                html_cust += "</div>";
                        });
                        $('.tracking-list').append(html_cust);
                    }
                            
                }
            });
        }
    });

    $(document).on('click','.CustCode',function(e){
        e.preventDefault();
        let custid      = $(this).data('custid');
        let Container   = $('.activeTr').data('contain');
        $.ajax({
            type: "post",
            url: url+"/CustItem",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {custid:custid,container:Container},
            beforeSend:function(){
                $('.ItemOrder tbody tr').remove();
            },
            success: function (response) {
                $('#DataItem').modal('show');
                let html = '';
                let i = 1;
            
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
                    html += "<td>"+value.GoodCode+"</td>"
                    html += "<td>"+value.GoodName+"</td>"
                    html += "<td>"+value.GoodQty+"</td>"
                    html += "<td>"+value.GoodUnit+"</td>"
                    html += "<td><span class=\""+bg_class+"\">"+bg_text+"</span></td>"
                    html += "</tr>"
                    i++;
                });
                $('#CustName').text(response[0].CustName);
                $('.ItemOrder tbody').append(html);

            }
        });
    });
</script>
@endsection
