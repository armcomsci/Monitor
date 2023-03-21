<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('theme/assets/img/favicon.ico') }}" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+Thai:400,600,700" rel="stylesheet">
    <link href="{{ asset('theme/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- toastr -->
    <link href="{{ asset('theme/plugins/notification/snackbar/snackbar.min.css') }}" rel="stylesheet" type="text/css" />

    @yield('css')

    <title>JT Driver Monitor</title>
</head>
<style>
    body{
        font-family: 'Noto Sans Thai', sans-serif !important;
    }
    .snackbar-container{
        height: auto !important;
    }
    .loadingWidget {
        position: relative;
        width: 100%;
        height: 100%;
        background: White url('https://api.nostramap.com/developer/V2/images/loader.gif') no-repeat fixed center center;
        filter: alpha(opacity=60);
        opacity: 0.6;
        z-index: 10000;
        vertical-align: middle;
        top: 0px;
        left: 0px;
    }
    .map {
        position: relative;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 510px;
    }
    .blink_me {
        animation: blinker 1s linear infinite;
    }
    .loadingWidget{
        opacity: 0 !important;
    }
    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
</style>
<body>

    <!--  BEGIN NAVBAR  -->
    @include('layout.header')
    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->
    @yield('sub-header')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

        @include('layout.menuleft')

        @yield('content')

    </div>
    @php
        $url = url('/');
    @endphp
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('theme/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('theme/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('theme/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.6.0/socket.io.js" integrity="sha512-rwu37NnL8piEGiFhe2c5j4GahN+gFsIn9k/0hkRY44iz0pc81tBNaUN56qF8X4fy+5pgAAgYi2C9FXdetne5sQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('theme/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
    <!-- toastr -->
    <script src="{{ asset('theme/plugins/notification/snackbar/snackbar.min.js') }}"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <script type="text/javascript" src="https://api.nostramap.com/nostraapi/v2.0/?key=Guh))FJkjZARKECd46rfcoQI53dnBDfmR2AOQc0KiJqdhf1e1i28Gskpn7CGLqYCxmAxLz9TPk1eMTRxdGcEFs0=====2"></script>
    @php
        $Empcode = auth()->user()->EmpCode;
    @endphp
    <script>
        const url    = '{{ $url }}';
        const socket = io.connect('https://xm.jtpackconnect.com:8443/');
        var EmpCode  = '{{ $Empcode }}';
       
        $(document).ready(function() {
            $('.logout').click(function (e) { 
                e.preventDefault();
                swal({
                    title: 'ออกจากระบบ',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    padding: '2em'
                }).then(function(result) {
                    console.log($(this).find('a').attr('href'));
                    if(result.value){
                        window.location.href = url+"/Logout";
                    }
                });
            });
            App.init();
            socket.on('Send_To_Monitor', (data) =>  {
          
                var res             = data[0]['CheckIn']['recordset']['0']
                var html            = res.EmpDriverFullName+" <br> ทะเบียนรถ : "+res.VehicleCode;
             

                Snackbar.show({
                    text: "<div style=\"padding:10px\" >"+html+"</div><div style=\"padding:10px\" >แสกนเข้ารับสินค้า !</div>",
                    pos: 'top-right',
                    maxWidth: '100%',
                    actionTextColor: '#fff',
                    backgroundColor: '#1abc9c',
                    duration: 5000,
                    actionText: 'X'
                });

                var html_check_in =  "<tr>";
                    html_check_in += "<td class=\"text-center text-success\">"+res.ContainerNO+"</td>";
                    html_check_in += "<td class=\"text-success\">"+res.EmpDriverFullName+"</td>";
                    html_check_in += "<td><span class=\"badge outline-badge-success shadow-none\">"+moment.utc(res.created_at).format('HH:mm')+"</span></td>";
                    html_check_in += "</tr>"; 

                $("#tb-last-checkin tbody").prepend(html_check_in).fadeIn(1000);
                $('#tb-last-checkin tr:last').remove();


                $.each(data[1]['CountCheckIN']['recordset'], function (i, value) {
                    let alltran = parseInt($('#All-EmpDrive-'+value.CarType).text());
                    let transp  = parseInt(value.transp);
                    let PerCent = (transp/alltran)*100;
                    PerCent = Math.round(PerCent);

                    if(PerCent < 80){
                        let colorBar = "bg-gradient-warning";
                    }else if(PerCent >= 80 && PerCent <= 100) {
                        let colorBar = "bg-gradient-success";
                    }
                    
                    $('#TranSp-'+value.CarType).text(value.transp);

                    $('#bar_transp_'+value.CarType).css('width',PerCent+"%");
                    $('#bar_transp_'+value.CarType).attr('aria-valuenow',PerCent);
                    $('#bar_transp_'+value.CarType).text(PerCent+"%");
                });
            });

            socket.on('Send_To_Monitor_Checkout', (data) =>  {
               
                var res  = data[0]['CheckOut']['recordset']['0']
                var html = res.EmpDriverFullName+" <br> ทะเบียนรถ : "+res.VehicleCode;
             
                Snackbar.show({
                    text:"<div style=\"padding:10px\" >"+html+"</div><div style=\"padding:10px\" >แสกนออกจากคลังสินค้า !</div>",
                    pos: 'top-right',
                    maxWidth: '100%',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                    duration: 5000,
                    actionText: 'X'
                });

                var html_check_out =  "<tr>";
                    html_check_out += "<td class=\"text-center text-danger\">"+res.ContainerNO+"</td>";
                    html_check_out += "<td class=\"text-danger\">"+res.EmpDriverFullName+"</td>";
                    html_check_out += "<td><span class=\"badge outline-badge-danger shadow-none\">"+moment.utc(res.updated_at).format('HH:mm')+"</span></td>";
                    html_check_out += "</tr>"; 

                $("#tb-last-checkout tbody").prepend(html_check_out).fadeIn(1000);
                $('#tb-last-checkout tr:last').remove();
                $.each(data[1]['CountCheckOut']['recordset'], function (i, value) {
                    let alltran = parseInt($('#All-EmpDrive-'+value.CarType).text());
                    let transp  = parseInt(value.transp);
                    let PerCent = (transp/alltran)*100;
                    PerCent = Math.round(PerCent);

                    if(PerCent < 80){
                        let colorBar = "bg-gradient-warning";
                    }else if(PerCent >= 80 && PerCent <= 100) {
                        let colorBar = "bg-gradient-success";
                    }

                    $('#TranSp-'+value.CarType).text(value.transp);

                    $('#bar_transp_'+value.CarType).css('width',PerCent+"%");
                    $('#bar_transp_'+value.CarType).attr('aria-valuenow',PerCent);
                    $('#bar_transp_'+value.CarType).text(PerCent+"%");
                });
            });

            socket.on('FormJob',(data)=>{
                if(data.Sendto == EmpCode){
                    Snackbar.show({
                        text: 'รับงานจาก : '+data.SendFormName+'<br> จำนวน : '+data.Amount+'งาน',
                        pos: 'top-right',
                        maxWidth: '100%',
                        actionTextColor: '#fff',
                        backgroundColor: '#e2a03f',
                        duration: 10000,
                        actionText: "อัพเดท",
                        onActionClick: (element) => {
                            element.style.opacity = 0;
                            $('#ReturnJobTrans').click();
                            // location.reload();
                        }
                    });
                    $('.NewJobReceive').text(data.Amount);
                }
            })
        });
    </script>
    <script src="{{ asset('theme/assets/js/custom.js') }}"></script>

    @yield('script')
</body>
</html>