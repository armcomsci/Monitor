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
            <div class="col-6  layout-spacing mt-1">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 layout-spacing mt-1">
                        <div class="widget widget-three">
                            <div class="widget-heading">
                            </div>
                        </div>
                        <div class="widget-content">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 layout-spacing mt-1">
                        <div class="widget widget-three">
                            <div class="widget-heading">
                            </div>
                        </div>
                        <div class="widget-content">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6  layout-spacing mt-1">
                <div class="row">
                    @foreach ($data['SumScore'] as $UserScore)
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 layout-spacing mt-1">
                        <div class="widget widget-three">
                            <div class="widget-heading" style="margin-bottom:10px;">
                               <h5>{{ $UserScore->Fullname }}</h5>
                            </div>
                            <div class="widget-content">
                                <div class="order-summary">
                                    <div class="summary-list">
                                        <div class="w-summary-details">
                                            <div class="w-summary-info">
                                                <p>
                                                <i class="fa-solid fa-trophy"></i> เดือน{{ MonthThai($Month) }} : <span id="text-score-{{ $UserScore->EmpCode }}">{{ number_format($UserScore->TotalScore,2) }}</span>
                                                </p>
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
                        <div id="dlgLoading" class="loadingWidget"></div>
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            {{-- Last Check IN --}}
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" style="height: 560px;">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>เข้างานล่าสุดของวันที่ {{ DateThai(date("Y-m-d"),false) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive" style="height: 510px; overflow-x: hidden;">
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
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" style="height: 560px;">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>ส่งสินค้าล่าสุดของวันที่ {{ DateThai(date("Y-m-d"),false) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive" style="height: 510px; overflow-x: hidden;">
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


        var initExtent, map, gLayer, route, routeLayer, layerMarker, chkRoutedResult;
        var points = [];
        nostra.onready = function () {
            loadCarMark();
            // initialize();
        };

        function showLoading() {
            document.getElementById("dlgLoading").style.display = "block";
        }
        function hideLoading() {
            document.getElementById("dlgLoading").style.display = "none";
        }

        function loadCarMark(){
            $.ajax({
                type: "get",
                url: url+"/GpsCarAll",
                dataType : 'json',
                // data: $('#FindLocal').serialize(),
                beforeSend: function() {
                    showLoading();
                },
                success: function (response) {
                    initialize(response);
                }
            });
        }
    
        function initialize(response) {
            showLoading();
   
            map = new nostra.maps.Map("map", {
                id: "mapTest",
                logo: true,
                scalebar: true,
                slider: true,
                level: 18,
                lat: 13.8916823,
                lon: 100.4118694
            });
            nostra.config.Language.setLanguage(nostra.language.L);
            layerMarker = new nostra.maps.layers.GraphicsLayer(map, { id: "layerMarker" });
            gLayer      = new nostra.maps.layers.GraphicsLayer(map, { id: "gLayerPoint" });

            map.addLayer(gLayer);
            map.addLayer(layerMarker);

            route = new nostra.services.network.route();
            route.country = "TH";

            map.events.load = function () {
                response.forEach( List => {
                    // console.log(List);
                    let lat     = List.lat;
                    let lon     = List.lon;
                    let name    = List.vehicle_id;

                    nostraCallout = new nostra.maps.Callout({ title: "ตำแหน่ง ", content: "ทะเบียนรถ : "+name });

                    var marker = new nostra.maps.symbols.Marker(
                    {
                        url: url+"/public/icon/location.png",
                        width: 32, 
                        height: 32, 
                        attributes: {POI_NAME: "ตำแหน่ง", POI_ROAD: name}, 
                        callout: nostraCallout, 
                        draggable: false, 
                        isAnimateHover: true
                    });
                    layerMarker.addMarker(lat, lon, marker);
                    const stop = new nostra.services.network.stopPoint({
                        lat: lat,
                        lon: lon,
                    })
                    route.addStopPoint(stop);
                    points.push([lat, lon]);
                });
            }

            setTimeout(() => {
                    map.setExtent(points)
            }, 1500);
            
            map.disableDoubleClickZoom();
            hideLoading();
        }
       
    </script>
@endsection