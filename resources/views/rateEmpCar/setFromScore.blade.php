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
 <link href="{{ asset('theme/assets/css/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
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
    .searchable-container .searchable-items.list .items.items-header-section h4{
        margin-left: 0px !important;
    }
    .item-content{
        cursor: pointer;
    }
    .table > tbody > tr > td{
        white-space: unset;
    }
    .activeItem{
        background: skyblue !important;
    }
    .setHeight{
        height: 750px; 
        overflow: auto; 
        overflow-x: hidden;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>สร้างฟอร์มประเมินคนรถ</span></li>
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
                            <div id="mailbox-inbox" class="accordion mailbox-inbox p-3 row">
                                @php
                                    $selected1 = '';
                                    $selected2 = '';
                                    $selected3 = '';

                                    if($carSize != ''){
                                       
                                        switch ($carSize) {
                                            case 'CT001':
                                                $selected1 = "selected";
                                                break;
                                            case 'CT002':
                                                $selected2 = "selected";
                                                break;
                                            case 'CT003':
                                                $selected3 = "selected";
                                                break;
                                        }
                                    }

                                    $selectedGroup1 = '';
                                    $selectedGroup2 = '';

                                    if($groupCode != ''){
                                        switch ($groupCode) {
                                            case 'A':
                                                $selectedGroup1 = "selected";
                                                break;
                                            case 'EG-0003':
                                                $selectedGroup2 = "selected";
                                                break;
                                            
                                        }
                                    }
                                @endphp
                                <div class="col-12 ">
                                    <form id="FormAddGroup" class="row" onSubmit="return false" method="post" action="{{ url()->current() }}">
                                        @csrf
                                        <div class="form-group col-2 mt-4">
                                            <select class="form-control" id="groupCode" name="groupCode" onchange="this.form.submit()" >
                                                <option value="A"  {{ $selectedGroup1 }}>พนักงานในบริษัท</option>
                                                <option value="EG-0003" {{ $selectedGroup2 }} >พนักงานนอกบริษัท</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <select class="form-control" id="carSize" name="carSize" onchange="this.form.submit()" >
                                                <option>---เลือกประเภทรถ---</option>
                                                <option value="CT001" {{ $selected1 }}>รถเล็ก</option>
                                                <option value="CT002" {{ $selected2 }}>รถกลาง</option>
                                                <option value="CT003" {{ $selected3 }}>รถใหญ่</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <select class="form-control" id="year" name="year" onchange="this.form.submit()">
                                                @php
                                                    $YearStart = 2024;
                                                    $Year = date('Y');
                                                @endphp
                                                @for ($i = $YearStart ; $i < $YearStart+10; $i++)
                                                    @php
                                                        $selectedYear = '';
                                                        if($Year == $i && $yearSelect == ''){
                                                            $selectedYear = "selected";
                                                        }elseif($yearSelect != '' && $yearSelect == $i){
                                                            $selectedYear = "selected";
                                                        }
                                                    @endphp
                                                    <option value="{{ $i }}" {{ $selectedYear }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-6 setHeight">
                                    
                                    <div class="layout-spacing layout-top-spacing pl-3" id="cancel-row">
                                        <div class="widget-content searchable-container list">
                                            <div class="col-xl-12 text-sm-right">
                                                <div class="d-flex bd-highlight">
                                                    @php
                                                        if($SumScore >= 100){
                                                            $color = "green";
                                                        }else{
                                                            $color = "red";
                                                        }
                                                    @endphp
                                                    <div class="mr-auto p-2 bd-highlight">
                                                        <h4 style="color:{{ $color }}">คะแนนรวมทุกหัวข้อ <span id="ScoreTitle">{{ $SumScore }}</span> คะแนน</h4>
                                                        <p style="color:red">***คะแนนแต่ละหัวข้อต้องรวมกันไม่เกิน 100 คะแนน***</p>
                                                    </div>
                                                    <div class="p-2 bd-highlight">
                                                        <button type="button" class="btn btn-outline-primary" id="Add">เพิ่มหัวข้อ</button>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($RateTitle != '' && count($RateTitle) > 0)
                                            <div class="searchable-items list">
                                                <div class="items items-header-section">
                                                    <div class="item-content">
                                                        <div style="width: 100px;">
                                                            <h4>ลำดับ</h4>
                                                        </div>
                                                        <div style="width: 400px;">
                                                            <h4>หัวข้อ</h4>
                                                        </div>
                                                        <div  style="width: 100px;">
                                                            <h4>คะแนน</h4>
                                                        </div>
                                                        <div class="">
                                                            <h4>แก้ไข/ลบ</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($RateTitle as $item)
                                                <div class="items subTitle" data-id="{{ $item->id }}">
                                                    <div class="item-content">
                                                        <div style="width: 100px;">
                                                            <p  class="info-title">{{ $i }}</p>
                                                        </div>
                                                        <div class="" style="width: 400px;">
                                                            <p  class="info-title">{{ $item->Title }}</p>
                                                        </div>
                                                        <div  style="width: 100px;">
                                                            <p  class="info-title">{{ $item->Score }}</p>
                                                        </div>
                                                        <div class="action-btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit editTitle" data-id="{{ $item->id }}"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2  delete-multiple delete" data-id="{{ $item->id }}"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                        </div>
                                                      
                                                    </div>
                                                </div>
                                                @php
                                                    $i++;
                                                @endphp
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-6 setHeight" id="DetailTitle">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AddTitle" tabindex="-1" role="dialog" aria-labelledby="AddTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มหัวข้อ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 210px;"> 
                <form id="SaveRateTitle" action="javascript:void(0);" >
                    <div class="form-row">
                        <div class="col-md-10 mb-4">
                            <label>หัวข้อ</label>
                            <input type="text" class="form-control required"  name="title" placeholder="หัวข้อ" >
                        </div>
                        <div class="col-md-2 mb-4">
                            <label>คะแนน</label>
                            <input type="text" class="form-control required" name="score" placeholder="คะแนน" >
                        </div>
                        <input type="hidden" name="type" id="type">
                    </div>
                    <button class="btn btn-primary mt-3" id="SaveTitle" type="submit">บันทึกข้อมูล</button>
                </form>
               
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalAddSubTitle" tabindex="-1" role="dialog" aria-labelledby="AddTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มหัวข้อย่อย</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" style="height: 210px;"> 
                <form id="SaveRateSubTitle" action="javascript:void(0);" >
                    <div class="form-row">
                        <div class="col-md-10 mb-4">
                            <label>หัวข้อ</label>
                            <input type="text" class="form-control requiredSub"  name="titleSub" placeholder="หัวข้อ" >
                        </div>
                        <div class="col-md-2 mb-4">
                            <label>คะแนน</label>
                            <input type="text" class="form-control requiredSub" name="scoreSub" placeholder="คะแนน" >
                        </div>
                        <input type="hidden" name="type" id="typeSub">
                    </div>
                    <button class="btn btn-primary mt-3" id="SaveSubTitle" type="submit">บันทึกข้อมูล</button>
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
    var idEdit;
    var idSubEdit;
    $(document).ready(function () {
        $("input[name='score'],input[name='scoreSub']").mask('000');
       

        $('#Add').click(function (e) { 
            e.preventDefault();
            let ScoreTitle = parseFloat($('#ScoreTitle').text());

            if(ScoreTitle >= 100){
                swal({
                    title: 'กรุณาแก้ไขคะแนน',
                    text: 'ไม่สามารถเพิ่มหัวข้อได้ เนื่องจากคะแนนรวมเท่ากับ 100',
                    type: 'warning',
                    padding: '2em'
                });
                return false;
            }

            $('#AddTitle').modal('show');
            $('#type').val('0');
            $("#SaveRateTitle").trigger('reset');
        });

        $('.editTitle').click(function(e){
            e.preventDefault();
            let id = $(this).data('id');
            idEdit = id;
            $.ajax({
                type: "post",
                url: url+"/RateEmpDrivGetTitle",
                data: {'id' : id},
                // dataType: "dataType",
                success: function (response) {
                    $("input[name='title']").val(response.Title);
                    $("input[name='score']").val(response.Score);
                    $('#type').val('1');
                    $('#AddTitle').modal('show');
                }
            });
        });

        $('#SaveRateTitle').submit(function (e) { 
            e.preventDefault();
            let required        = $('.required');
            let required_status = true;
            let type            = $('#type').val();
            let CarType         = $('#carSize').val();
            let groupCode       = $('#groupCode').val();
            let year            = $('#year').val();

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
                let FormSave = $(this).serializeArray();
                FormSave.push({ name : 'CarType', value : CarType },{ name : 'Year', value : year},{ name : 'groupCode', value : groupCode});

                if(type == 1){
                    FormSave = $(this).serializeArray();
                    FormSave.push({ name : 'id', value : idEdit });
                }
                // console.log(FormSave);
                $.ajax({
                    type: "post",
                    url: url+"/saveRateCarDriv",
                    data: FormSave,
                    // dataType: "dataType",
                    beforeSend:function(){
                        // showLoading();
                        $('#SaveTitle').attr('disabled',true);
                    },
                    success: function (response) {
                        $('#SaveTitle').attr('disabled',false);
                        if(response == 'success'){
                            swal({
                                title: 'บันทึกสำเร็จ',
                                text: '',
                                type: 'success',
                                padding: '2em'
                            }).then((result) => {
                                location.reload();
                            });
                        }else if(response == 'scoreError'){
                            swal({
                                title: 'Error',
                                text: 'ไม่สามรถเพิ่มหัวข้อได้เนื่องจากคะแนนมากกว่า 100',
                                type: 'error',
                                padding: '2em'
                            })
                        }else if(response == 'error'){
                            swal({
                                title: 'Error',
                                text: '',
                                type: 'error',
                                padding: '2em'
                            })
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

        $('.subTitle').click(function (e) { 
            e.preventDefault();
            let id = $(this).data('id');

            $('.subTitle').children(".item-content").removeClass('activeItem');

            $(this).children(".item-content").addClass('activeItem');
            $.ajax({
                type: "post",
                url: url+"/RateEmpDrivGetSubTitle",
                data: {'id' : id},
                // dataType: "dataType",
                success: function (response) {
                    $('#DetailTitle').html(response);
                    
                }
            });
        });

    });

    $(document).on('click','#AddSubTitle', function (e) {
        e.preventDefault();
        let ScoreTitle = parseFloat($('#ScoreSubTitle').text());
        let TotalSubScore = parseFloat($('#TotalSubScore').text());
        if(ScoreTitle >= TotalSubScore){
            swal({
                title: 'กรุณาแก้ไขคะแนน',
                text: 'ไม่สามารถเพิ่มหัวข้อได้ เนื่องจากคะแนนรวมเท่ากับ '+TotalSubScore,
                type: 'warning',
                padding: '2em'
            });
            return false;
        }

        $('#ModalAddSubTitle').modal('show');
        $('#typeSub').val('0');
        $("#SaveRateSubTitle").trigger('reset');
    });

    $(document).on('click','.delete', function (e) {
        e.preventDefault();
            let id = $(this).data('id');
            swal({
                    title: 'ต้องการลบข้อมูล',
                    text: "หากลบข้อมูลส่วนค่าเริ่มต้นอาจจะทำให้เกิดผลกระทบกับส่วนอื่นๆ ได้",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: url+"/RateEmpDrivDeleteTitle",
                        data: {'id' : id},
                        // dataType: "dataType",
                        beforeSend:function(){
                            // showLoading();
                            // $('#map').empty();
                        },
                        success: function (response) {
                            if(response == 'success'){
                                swal({
                                    title: 'ลบข้อมูลสำเร็จ',
                                    text: '',
                                    type: 'success',
                                    padding: '2em'
                                }).then((result) => {
                                    $('.activeItem').click();
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
        })  
    });

    $(document).on('click','.editSubTitle', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        idSubEdit = id;
        $.ajax({
            type: "post",
            url: url+"/RateEmpDrivGetTitle",
            data: {'id' : id},
            // dataType: "dataType",
            success: function (response) {
                $("input[name='titleSub']").val(response.Title);
                $("input[name='scoreSub']").val(response.Score);
                $('#typeSub').val('1');
                $('#ModalAddSubTitle').modal('show');
            }
        });
    });

    $(document).on('submit','#SaveRateSubTitle', function (e) {
        e.preventDefault();
        let required        = $('.requiredSub');
        let required_status = true;
        let type            = $('#typeSub').val();
        let CarType         = $('#carSize').val();
        let groupCode       = $('#groupCode').val();
        let year            = $('#year').val();
        let MainID          = $('.subTitle').children(".activeItem");
        MainID              = MainID.parent().data('id');

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
            let FormSave = $(this).serializeArray();
            FormSave.push({ name : 'CarType', value : CarType },{ name : 'MainID', value : MainID },{ name : 'Year', value : year},{ name : 'groupCode', value : groupCode});
            // FormSave.push({ name : 'MainID', value : MainID });

            if(type == 1){
                FormSave.push({ name : 'idSubEdit', value : idSubEdit });
            }
            // console.log(FormSave);
            $.ajax({
                type: "post",
                url: url+"/saveRateSubTitleCarDriv",
                data: FormSave,
                // dataType: "dataType",
                beforeSend:function(){
                    // showLoading();
                    // $('#SaveSubTitle').attr('disabled',true);
                },
                success: function (response) {
                    $('#SaveSubTitle').attr('disabled',false);
                    if(response == 'success'){
                        swal({
                            title: 'บันทึกสำเร็จ',
                            text: '',
                            type: 'success',
                            padding: '2em'
                        }).then((result) => {
                            $('#ModalAddSubTitle').modal('hide');
                            $('.activeItem').click();
                        });
                    }else if(response == 'scoreError'){
                        swal({
                            title: 'Error',
                            text: 'ไม่สามรถเพิ่มหัวข้อได้เนื่องจากคะแนนมากกว่ากำหนด',
                            type: 'error',
                            padding: '2em'
                        })
                    }else if(response == 'error'){
                        swal({
                            title: 'Error',
                            text: '',
                            type: 'error',
                            padding: '2em'
                        })
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
</script>
@endsection