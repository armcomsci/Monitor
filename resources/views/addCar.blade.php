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
 <link rel="stylesheet" href="{{ asset('theme/assets/css/fullcalendar.min.css') }}">
 <style>
    .fc-basic-view .fc-content {
        color: #1c1c1c !important;
        font-size: 16px;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>กำหนดเที่ยวรถ</span></li>
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
                                    <div class="col-xl-12 col-md-12">
                                        <div id='calendar' class='calendar'></div>
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
<div class="modal fade " id="SetCar" tabindex="-1" role="dialog" aria-labelledby="DateNow" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" id="DateNow"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="SaveSetCar">
                    @csrf
                    @foreach ($CarType as $item)
                        <div class="form-group row">
                            <label for="input-{{ $item->TranspID }}"  class="offset-1 col-3 col-form-label">{{ $item->TranspName }}</label>
                            <div class="col-7">
                                <input type="text" class="form-control tranSp" name="TranSp[{{ $item->TranspID }}]" id="input-{{ $item->TranspID }}" placeholder="จำนวนเที่ยวรถ" autocomplete="off">
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group row">
                        <input type="hidden" name="sendDate" id="sendDate" value="">
                        <div class="offset-1 col-sm-10">
                            <button type="submit" class="btn  btn-primary">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('theme/assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('theme/assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/fullcalendar.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/moment.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale-all.js"></script>
<script>
        $('.tranSp').mask('0000');
        $('#calendar').fullCalendar({
            select: function( start, end, jsEvent, view ) {
                const DateNow    = moment(start).format('LL');
                const StartDay   = moment(start).format('YYYY-MM-DD');
                $.ajax({
                    type: "post",
                    url: url+"/GetEventSet",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {StartDay : StartDay},
                    dataType: "json",
                    success: function (response) {
                        if(response != ""){
                            $(response).each(function(index, value) {
                                $("input[name='TranSp["+value.TranspId+"]']").val(value.Amount);
                            });
                        }else{
                            $('#SaveSetCar').trigger("reset");
                        }
                       
                        $('#DateNow').text('กำหนดคนรถของวันที่ '+DateNow);
                        $('#sendDate').val(StartDay);
                        $('#SetCar').modal('show');
                    }
                });
                
            },
            events: function(start, end, timezone, callback) {
                // console.log(start,end)

                var colorDay = {
                    '0':
                        {
                            borderColor : '#ff6961',
                            backgroundColor : '#ff6961',
                            textColor : '#fff'
                        },
                    '1':
                    {
                        borderColor : '#FDFD96',
                        backgroundColor : '#FDFD96',
                        textColor : '#000000'
                    },
                    '2':
                    {
                        borderColor : '#ffc0cb',
                        backgroundColor : '#ffc0cb',
                        textColor : '#fff'
                    },
                    '3':
                    {
                        borderColor : '#77DD77',
                        backgroundColor : '#77DD77',
                        textColor : '#fff'
                    },
                    '4':
                    {
                        borderColor : '#FFB347',
                        backgroundColor : '#FFB347',
                        textColor : '#fff'
                    },
                    '5':
                    {
                        borderColor : '#00bfff',
                        backgroundColor : '#00bfff',
                        textColor : '#fff'
                    },
                    '6':
                    {
                        borderColor : '#C3B1E1',
                        backgroundColor : '#C3B1E1',
                        textColor : '#fff'
                    },
                }

                $.ajax({
                    type: "get",
                    url: url+"/EventCarSet",
                    dataType: 'json',
                    success: function (response) {
                        var events = [];
                        // console.log(response);
                        $(response).each(function(index, value) {
                            const date_1    = moment(value.SendDate);
                            const dow       = date_1.day();
                            console.log(colorDay,dow);
                            events.push({
                                title : value.TranspName,
                                description: value.Amount,
                                start : value.SendDate,
                                borderColor : colorDay[dow]['borderColor'],
                                backgroundColor: colorDay[dow]['backgroundColor'],
                                textColor: colorDay[dow]['textColor']
                            });
                        });
                        // console.log(events);
                        callback(events);
                    }
                });
            },
            eventRender: function(eventObj, $el) {
            
                $el.popover({
                    title: eventObj.title,
                    content:  'จำนวนเที่ยวรถ : '+eventObj.description,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body'
                });
                $el.find(".fc-title").append('<span class="badge badge-light" style="float:right">'+eventObj.description+'</span>');
            },
            defaultView: 'basicWeek',
            locale: 'th',
            selectHelper: true,
            selectable: true,
            selectConstraint: {
                start: $.fullCalendar.moment().subtract(1, 'days'),
                end: $.fullCalendar.moment().startOf('month').add(2, 'month')
            }
        });
        $("#SaveSetCar").submit(function (e) { 
            e.preventDefault();
            // let TranSp = $(".tranSp").val();
            let checkVal = false;
            $(".tranSp").each(function(index, value) {
                let val = $(this).val();
                if(val != ""){
                    checkVal = true;
                    return false;
                }
            });
            if(checkVal){
                $.ajax({
                    type: "post",
                    url: url+"/SaveTranspDate",
                    data: $('#SaveSetCar').serialize(),
                    // dataType: "dataType",
                    success: function (response) {
                        if(response == 'success'){
                            $('#SetCar').modal('hide');
                            $('#calendar').fullCalendar('refetchEvents');
                        }else{
                            alert('error :'+response);
                        }
                    }
                });
            }else{
                return false;
            }
        });
</script>
@endsection
