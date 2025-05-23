@extends('layout.template')

@section('css')
<link href="{{ asset('theme/plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('theme/assets/css/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="{{ asset('theme/assets/css/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('theme/plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('theme/assets/css/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('theme/assets/css/tables/table-basic.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('theme/assets/css/components/custom-counter.css') }}" rel="stylesheet" type="text/css">
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

<style>
    .progress-lg{
        height: 20px !important;
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
                            <li class="breadcrumb-item active" aria-current="page"><span>Dashboard</span></li>
                        </ol>
                    </nav>
                </div>
            </li>
        </ul>
    </header>
</div>  
@endsection

@section('content')
@php
    $Month     =   date('m',time());
@endphp
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            @foreach ($data['Workdate'] as $item)
            @php
                $CarType = $item->CarType;
                $SumCarType = 0;
                if( $data['SumCarDriv_'.$CarType] != "" ){
                   $SumCarType = $data['SumCarDriv_'.$CarType]->SumEmp;
                }
                $perCentEmp =  round(($SumCarType/$item->SumDrive)*100, 0, PHP_ROUND_HALF_UP);

                if($perCentEmp < 80){
                    $colorBar = "bg-gradient-warning";
                }else if($perCentEmp >= 80 ) {
                    $colorBar = "bg-gradient-success";
                }
                // $colorBar = "bg-gradient-success";
                
            @endphp
            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing mt-1">
                <div class="widget widget-three">
                    <div class="widget-heading">
                        <h5 class="">{{ $item->CarTypeName }}</h5>
                    </div>
                    <div class="widget-content">
                        <div class="order-summary">
                            <div class="summary-list">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </div>
                                <div class="w-summary-details">
                                    
                                    <div class="w-summary-info">
                                        <h6>รถบริษัท</h6>
                                        <p class="summary-count"><span id="TranSp-{{ $item->CarType }}">{{ $data['SumCarDriv_'.$CarType] != "" ? $data['SumCarDriv_'.$CarType]->SumEmp : 0  }}</span>/<span id="All-EmpDrive-{{ $item->CarType }}">{{ $item->SumDrive  }}</span></p>
                                    </div>
                                    <div class="w-summary-stats">
                                        <div class="progress br-30 progress-lg">
                                            <div class="progress-bar {{ $colorBar }}" id="bar_transp_{{ $item->CarType }}" role="progressbar" style="width: {{ $perCentEmp }}%" aria-valuenow="{{ $perCentEmp }}" aria-valuemin="0" aria-valuemax="100">{{ $perCentEmp."%" }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>       
                    </div>
                </div>
            </div>            
            @endforeach
            <div class="col-6  layout-spacing mt-1">
                <div class="row">
                    @foreach ($data['SumScore'] as $UserScore)
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 layout-spacing mt-1">
                        <div class="widget widget-three">
                            <div class="widget-heading" style="margin-bottom:10px;">
                               <h4>{{ $UserScore->Fullname }}</h4>
                            </div>
                            <div class="widget-content">
                                <div class="order-summary">
                                    <div class="summary-list">
                                        <div class="w-summary-details">
                                            <div class="w-summary-info">
                                                <h5>
                                                <i class="fa-solid fa-trophy"></i> เดือน{{ MonthThai($Month) }} : <span id="text-score-{{ $UserScore->EmpCode }}">{{ number_format($UserScore->TotalScore,2) }}</span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-6  layout-spacing mt-1">
                <div class="row">
                    {{-- Last Check IN --}}
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" style="height: 350px;">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>เข้างานล่าสุดของวันที่ {{ DateThai(date("Y-m-d"),false) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive" style="height: 300px; overflow-x: hidden;">
                                    <table class="table mb-4" id="tb-last-checkin">
                                        <thead>
                                            <tr>
                                                <th>เลขตู้/คนรถ</th>
                                                <th>เวลา</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['LastCheckIN'] as $lastCheckIN)
                                                <tr>
                                                    <td class="text-success">
                                                        #{{ $lastCheckIN->ContainerNO }}
                                                        <br>
                                                        {{ $lastCheckIN->EmpDriverName." ".$lastCheckIN->EmpDriverLastName }}
                                                    </td>
                                                    <td><span class="badge outline-badge-success shadow-none">{{ ShowDate($lastCheckIN->created_at,"H:i") }}</span></td>
                                                </tr>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Last Check Out --}}
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" style="height: 350px;">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>ส่งสินค้าล่าสุดของวันที่ {{ DateThai(date("Y-m-d"),false) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive" style="height: 300px; overflow-x: hidden;">
                                    <table class="table mb-4" id="tb-last-checkout" >
                                        <thead>
                                            <tr>
                                                <th>เลขตู้/คนรถ</th>
                                                <th>เวลา</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['LastCheckOut'] as $LastCheckOut)
                                                <tr>
                                                    <td class="text-danger">
                                                        #{{ $LastCheckOut->ContainerNO }}
                                                        <br>
                                                        {{ $LastCheckOut->EmpDriverName." ".$LastCheckOut->EmpDriverLastName }}
                                                    </td>
                                                    <td><span class="badge outline-badge-danger shadow-none">{{ ShowDate($LastCheckOut->updated_at,"H:i") }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
           
           
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" >
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>ตำแหน่งรถล่าสุดของวันที่  {{ DateThai(date("Y-m-d"),false) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{-- <div id="dlgLoading" class="loadingWidget"></div> --}}
                        <div id="mapAll" style="height: 350px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6 layout-spacing mt-1">
                <div class="widget widget-three">
                    <div class="widget-heading mb-3">
                       <h5>10อันดับ เที่ยวรถสูงสุดประจำเดือน{{ MonthThai($Month) }}</h5>
                    </div>
                    <div class="widget-content">
                        <div class="table-responsive" style="height: 550px; overflow-x: hidden;">
                            <table class="table mb-4" style="font-size: 16px;">
                                <thead>
                                    <tr>
                                        <th>รหัส/คนรถ</th>
                                        <th>จำนวน/เที่ยว</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $a = 1;
                                    @endphp
                                    @foreach ($data['SumRun'] as $EmpRun)
                                        @php
                                            if($a > 11){
                                                break;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $EmpRun['EmpName'] }}</td>
                                            <td>{{ $EmpRun['StampSum'] }}</td>
                                        </tr>
                                        @php
                                            $a++;
                                        @endphp
                                    @endforeach

                                </tbody>
                            </table>
                        </div>   
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6 layout-spacing mt-1">
                <div class="widget widget-three">
                    <div class="widget-heading mb-3">
                        <h5>10อันดับ หมายเหตุล่าสุด</h5>
                    </div>
                    <div class="widget-content">
                        <div class="table-responsive" style="height: 550px; overflow-x: hidden;">
                            <table class="table mb-4">
                                <thead>
                                    <tr>
                                        <th>เลขตู้/คนรถ</th>
                                        <th>หมายเหตุ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['Comment'] as $Comment)
                                    <tr>
                                        <td>
                                            {{ $Comment->ContainerNo }}
                                            <br>
                                            {{ $Comment->EmpName }}
                                        </td>
                                        <td>
                                            {{ $Comment->Remark }}
                                            <br>
                                            เวลา : {{ ShowDate($Comment->RemarkTime,"d-m-Y H:i") }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('theme/plugins/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/dashboard/dash_1.js') }}"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('theme/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('theme/plugins/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/apex/custom-apexcharts.js') }}"></script>
    <script src="{{ asset('theme/assets/js/scrollspyNav.js') }}"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <script src="{{ asset('theme/plugins/counter/jquery.countTo.js') }}"></script>
  
    <script type="text/javascript">


        // var initExtent, map, gLayer, route, routeLayer, layerMarker, chkRoutedResult;
        var points = [];
      
        // function showLoading() {
        //     document.getElementById("dlgLoading").style.display = "block";
        // }
        // function hideLoading() {
        //     document.getElementById("dlgLoading").style.display = "none";
        // }

        function loadCarMark(){
            $.ajax({
                type: "get",
                url: url+"/GpsCarAll",
                dataType : 'json',
                // data: $('#FindLocal').serialize(),
                beforeSend: function() {
                    // showLoading();
                },
                success: function (response) {
                    initialize(response);
                }
            });
        }
    
        function initialize(response) {

            // Map options
            var mapOptions = {
                zoom: 6,
                center: { lat: 13.858573, lng: 100.3791033 }, // Initial center (adjust as needed)
                scrollwheel: true, // Set to false to disable scroll zoom
                gestureHandling: 'auto' // Can be 'cooperative', 'none', or 'greedy'
            };

            var map = new google.maps.Map(document.getElementById("mapAll"), mapOptions);

            const convertedData = [];
            // // console.log(response);
            response.forEach(record => {
                convertedData.push({
                    coords: {
                        lat: parseFloat(record.lat), // Convert string to float
                        lng: parseFloat(record.lon)  // Convert string to float
                    },
                    title : {
                        vehicle_id : record.vehicle_id
                    }
                });
            });

            // Create a new LatLngBounds object
            var bounds = new google.maps.LatLngBounds();

            // Loop through markers and add them to the map
            convertedData.forEach(function (markerInfo) {
                // console.log(markerInfo.coords);
                var marker = new google.maps.Marker({
                    position: markerInfo.coords,
                    map: map,
                    title: markerInfo.title.vehicle_id
                });

                // Extend the bounds to include each marker's position
                bounds.extend(marker.position);

                const infoWindow = new google.maps.InfoWindow({
                    content: `<h4>ทะเบียนรถ : ${markerInfo.title.vehicle_id}</h4>`
                });

                // Add a click listener to open the InfoWindow
                marker.addListener("click", function () {
                    infoWindow.open(map, marker);
                });
            });

            // Adjust the map to fit all markers
            map.fitBounds(bounds);
        }
        $(document).ready(function () {
            loadCarMark();
        });
   
       
    </script>
@endsection