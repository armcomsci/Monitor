
<div class="row">
    <div class="col-7" style="height: 520px;">
        <h4>ข้อมูลคนรถ/แผนที่ 
            <span style="float: right"><img src="{{ asset('/icon/timeline.png') }}" style="width: 56px; height: 56px;" title="TimeLine" class="timeline"></span> 
            @php
                $link['EmpCode']     = auth()->user()->EmpCode;
                $link['EmpDrivCode'] = $Data['Drive']->EmpDriverCode;
                $link['EmpGroupCode']= $Data['Drive']->EmpGroupCode;

                $json = json_encode($link,true);
                $json = base64_encode($json);
            @endphp
            <span style="float: right; margin-right: 25px; cursor: pointer;">
                <a onClick="MyWindow=window.open('https://jtxm.jtpackconnect.com:4316/webreport/lms/RateEmp/{{ $json }}','MyWindow','width=1200,height=680'); return false;"><img src="{{ asset('/icon/performance.png') }}" style="width: 56px; height: 56px;" ></a>
            </span>
        </h4>
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
        @if(isset($Data['location']->trx_date))
            <p>แผนที่อัพเดทเมื่อเวลา : {{ ShowDate($Data['location']->trx_date) }}</p>
        @endif
       
        {{-- <div id="dlgLoading" class="loadingWidget"></div> --}}
        <div id="mapDt" style="height: 270px; width: 100%;"></div>
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
        $('#mapDt').addClass('border-map');
       
        // console.log(dataApi);
    
        if(dataApi['location'] !== null){
            
            var mapOptions = {
                zoom: 6,
                center: { lat: 13.858573, lng: 100.3791033 }, // Initial center (adjust as needed)
                scrollwheel: true, // Set to false to disable scroll zoom
                gestureHandling: 'auto' // Can be 'cooperative', 'none', or 'greedy'
            };

            var map = new google.maps.Map(document.getElementById("mapDt"), mapOptions);

         

            if(dataApi['Route'] != null && dataApi['Route'].length != 0){
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer();
                directionsRenderer.setMap(map);

                const waypoints =   dataApi['Route'].map((item, index) => ({
                                        location: new google.maps.LatLng(parseFloat(item.Late), parseFloat(item.Long)),
                                        stopover: true, // Specify whether this location is a stopover point
                                        title: `${item.CustName} - ${item.ShiptoAddr1}`, // Add a label for the marker
                                    }));


                const destination = waypoints.pop().location; // Last item is the destination

                const request = {
                    origin: { lat: 13.858573, lng: 100.3791033}, 
                    destination: destination, 
                    waypoints: waypoints.map(
                            waypoint => ({ 
                                location: waypoint.location, 
                                stopover: waypoint.stopover 
                            })
                        ),
                    travelMode: google.maps.TravelMode.DRIVING,
                };

                directionsService.route(request, (result, status) => {
                    if (status == "OK") {
                        directionsRenderer.setDirections(result);
                    } else {
                        window.alert("Directions request failed due to " + status);
                    }
                });
            }
            
            const carMarker =   new google.maps.Marker({
                position: new google.maps.LatLng(parseFloat(dataApi['location'].lat), parseFloat(dataApi['location'].lon)),
                map: map,
                icon: {
                    url: url+'/icon/car.png', // Use a custom car icon if needed
                    scaledSize: new google.maps.Size(40, 40), // Resize the icon if needed
                },
                title: `${dataApi['location'].vehicle_id}`, // Marker label
            });

            // Add a click listener to open the InfoWindow
            google.maps.event.addListener(carMarker,"click", function () {
                const infoWindow = new google.maps.InfoWindow({
                    content: `<h4>ทะเบียนรถ : ${dataApi['location'].vehicle_id}</h4>`
                });
                infoWindow.open(map, carMarker);
            });
               
        
        }else{

            $('#mapDt').html("<img src='"+url+"/icon/not-found.png' class='text-center' style='margin-left: auto; margin-right: auto; display: block;' /><h2 style='color:red;' class='text-center mt-5' >X ไม่พบตำแหน่ง GPS</h2>")
        }
    }

    initialize(dataApi);
    // hideLoading();
   
</script>