<div class="row">
    <div class="col-7" style="height: 520px;">
        <h4>ข้อมูลคนรถ/แผนที่ <span style="float: right"><img src="{{ asset('/icon/timeline.png') }}" style="width: 56px; height: 56px;" title="TimeLine" class="timeline"></span></h4>
        <div class="d-flex justify-content-between mb-2">
            <div>
                <div class="avatar">
                    <img alt="avatar" src="https://images.jtpackconnect.com/empdrive/{{ $Data['Drive']->EmpDriverCode.".jpg" }}"  class="rounded-circle hidden-list" onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';" />
                    <span class="hiddenimg">
                        <img  src="https://images.jtpackconnect.com/empdrive/{{ $Data['Drive']->EmpDriverCode.".jpg" }}"  style="width: 250px; height: 250px;"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"/>
                    </span>
                </div>
                <h5>ชื่อ-นามสกุล : {{ $Data['Drive']->EmpDriverName." ".$Data['Drive']->EmpDriverlastName }} </h5>
                <h5>เบอร์ติดต่อ : {{ $Data['Drive']->EmpDriverTel }}</h5>
            </div>
            <div>
                <div style="padding-top: 65px;">
                    @if(isset($Data['AddBill']) && $Data['AddBill']->Addbill_Time != "")
                        <span class="badge outline-badge-warning " id="AddBillTime"> วางบิลเมื่อ : {{ $Data['AddBill']->Addbill_Time }} </span>
                    @endif
                </div>
                {{-- <div class="mt-2">
                    <button class="btn btn-outline-danger" style="float: right;" id="closeJob">ปิดงาน</button>
                </div> --}}
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <h4>ตำแหน่งรถ</h4>
            <button class="btn btn-success mb-2 mr-2"><i class="fa-sharp fa-solid fa-road"></i> อัพเดทแผนที่</button>
        </div>
        <p>แผนที่อัพเดทเมื่อเวลา : {{ ShowDate($Data['location']->trx_date) }}</p>
        
        {{-- <div id="dlgLoading" class="loadingWidget"></div> --}}
        <div id="map"></div>
    </div>
    <div class="col-5" style="height: 520px;">
        <div class="d-flex justify-content-start">
            <h4>สถานะจัดส่ง</h4>
        </div>
        <div class="table-responsive" style="height: 470px">
            <table class="table table-bordered table-hover table-condensed mb-4">
                <thead style="background-color: #aafff7;">
                    <tr>
                        <th>ลำดับ</th>
                        <th>ลูกค้า</th>
                        <th>จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($Data['Order'] as $order)
                        @php
                            $classAlert = '';
                            if($order->Flag_st == 'Y'){
                                $classAlert = "successFlag";
                            }elseif($order->Flag_st == 'N'){
                                $classAlert = "alertFlag";
                            }
                        @endphp
                        <tr class="{{ $classAlert }} CustCode" data-custid="{{ $order->CustID }}">
                            <td>{{ $i }}</td>
                            <td>{{ $order->CustName }}</td>
                            <td>{{ $order->SumQty }}</td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-7">
        <div class="d-flex justify-content-start">
            <h4>หมายเหตุณ์</h4>
        </div>
        <div class="table-responsive" style="height: 170px">
            <table class="table table-bordered table-hover table-condensed mb-4 event" style="overflow-x: auto">
                <thead style="background-color: #ffb347;">
                    <tr>
                        <th>ลำดับ</th>
                        <th>หมายเหตุณ์</th>
                        <th>เวลา</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($Data['Remark'] as $remark)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $remark->Remark }}</td>
                        <td><span class="badge outline-badge-success shadow-none">{{ ShowDate($remark->Datetime,"d-m-Y H:i") }}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </tbody>
        </div>
    </div>
    <div class="col-5">
        
    </div>
</div>
<script>
    dataApi = {!! json_encode($Data,true) !!};

    function initialize(dataApi) {
        $('#map').addClass('border-map');

        if(dataApi['location'] != null){
            var points = [];
            map = new nostra.maps.Map("map", {
                id: "mapTest",
                height : "350px",
                logo: true,
                scalebar: true,
                slider: true,
                level: 16,
                lat: dataApi['location'].lat,
                lon: dataApi['location'].lon
            });

            nostra.config.Language.setLanguage(nostra.language.L);
            layerMarker = new nostra.maps.layers.GraphicsLayer(map, { id: "layerMarker" });
            gLayer      = new nostra.maps.layers.GraphicsLayer(map, { id: "gLayerPoint" });
            routeLayer  = new nostra.maps.layers.GraphicsLayer(map, { id: "routeLayer" });
            carLayer    = new nostra.maps.layers.GraphicsLayer(map, { id: "carLayer" });

            map.addLayer(gLayer);
            map.addLayer(layerMarker);
            map.addLayer(routeLayer);
            map.addLayer(carLayer);

            route = new nostra.services.network.route();
            route.country = "TH";

            map.events.load = function () {
                if(dataApi['Route'] != null){
                    // ------------- เพิ่มจุดเริ่มต้น -----------------
                    let name,lat,lon
                    let Marker = new Array({
                        name :  "JT Branch 3",
                        lat  :  "13.858573",
                        lon  :  "100.3791033",
                        Addr :  "JT Branch 3"
                    });
                   
                    points.push([13.858573, 100.3791033]);
                    const stop = new nostra.services.network.stopPoint({
                        lat: 13.858573,
                        lon: 100.3791033,
                    })
                    route.addStopPoint(stop);
                    // ------------------------------------------

                    let a = 0;
                    dataApi['Route'].forEach( List => {
                     
                        lat      = List.Late;
                        lon      = List.Long;
                        name     = List.CustName;

                        Marker.push({
                            name : name,
                            lat  : lat,
                            lon  : lon,
                            Addr : List.ShiptoAddr1
                        });
                        points.push([lat, lon]);
                        isFirstLoad = false;

                        const stop = new nostra.services.network.stopPoint({
                            lat: lat,
                            lon: lon,
                        })
                        route.addStopPoint(stop);
                        a++;
                    });
                    // console.log(Marker);
                    route.returnedRouteDetail = "true";
                    
                    route.solve((result) => {
                        routeLayer.clear();
                        carLayer.clear();
                        route.clearStopPoint();

                        if (result.isRouteDetailReturned == "true" || result.isRouteDetailReturned == true) {
         
                            routeLayer.addRoute(result,"#009EFF", 1);
                            
                            // //ทำการซูมแบบ focus ไปยังเส้นทางที่วาด
                            map.zoomToNetworkResult(result);

                            if (result.nostraResults != null) {
                                routeStopPoint = result.nostraResults;
                            } else {
                                routeStopPoint = result.agsResults;
                            }
                        } else {
                            if (result.nostraResults != null) {
                                routeStopPoint = result.nostraResults;
                            } else {
                                routeStopPoint = result.agsResults; 
                            }
                        }

                        var imgCarTempUrl = "https://developer-test.nostramap.com/developer/V2/images/car_topview.png";
                        carMarker = new nostra.maps.symbols.Marker({
                            url: imgCarTempUrl, width: 27, height: 50
                        });
                        carLayer.addMarker(dataApi['location'].lat,dataApi['location'].lon, carMarker);

                        points.push([dataApi['location'].lat, dataApi['location'].lon]);
                    });

                    console.log(Marker);
                    let i = 1;
                    Marker.forEach(mark => {
                        
                        nostraCallout = new nostra.maps.Callout({ title: mark.name, content: "ตำแหน่ง : "+mark.Addr });
                        var marker = new nostra.maps.symbols.Marker(
                        {
                            url: "https://jtpackoffoods.co.th/Ecommerce/images/pin_"+i+".png",
                            width: 42, 
                            height: 42, 
                            attributes: {POI_NAME: "ตำแหน่ง", POI_ROAD: name}, 
                            callout: nostraCallout, 
                            draggable: false, 
                            isAnimateHover: true
                        });
                        layerMarker.addMarker(mark.lat, mark.lon, marker);
                        i++;
                    });
                }
            }

            setTimeout(() => {
                    map.setExtent(points)
            }, 1000);

            // hideLoading();
        }else{
            $('#map').html('<h2>ไม่พบตำแหน่ง GPS</h2>')
        }
    }
    initialize(dataApi);
</script>