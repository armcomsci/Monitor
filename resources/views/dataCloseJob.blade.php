@php
$Carsize = '';
switch ($res['CarType']) {
    case 'CT001':
        $Carsize = 'รถเล็ก';
        break;
    case 'CT002':
        $Carsize = 'รถกลาง';
        break;
    case 'CT003':
        $Carsize = 'รถใหญ่';
        break;
}

@endphp
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
        <form id="general-info" class="section general-info">
            <div class="info">
                <h5 class="">
                    ข้อมูลโดยรวมตู้ : {{ $res['Container'] }}
                    @if (isset($res['SendForm']))
                        <span style="float: right;">ผู้โอนงานให้ล่าสุด : {{ $res['SendForm'] }}</span>
                    @endif
                </h5>
                <div class="row">
                    <div class="col-lg-11 mx-auto">
                        <div class="row">
                            <div class="col-xl-2 col-lg-12 col-md-4">
                                <div class="upload mt-4 pr-md-4">
                                    <div class="avatar avatar-xl">
                                        <img alt="avatar" src="https://images.jtpackconnect.com/empdrive/{{ $res['EmpDriverCode'].".jpg" }}"  class="rounded hiddentxt" onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';" />
                                        <span class="hiddenimg">
                                            <img   src="https://images.jtpackconnect.com/empdrive/{{ $res['EmpDriverCode'].".jpg" }}"  style="width: 250px; height: 250px;"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                <div class="form">
                                    <div class="row">
                                        <div class="col-sm-7 pt-4">
                                            <h5>ชื่อ-นามสกุล : {{ $res['DriveName'] }}</h5>
                                            <h5>เบอร์โทร : {{ $res['DriveTel'] }}</h5>
                                        </div>
                                        <div class="col-sm-5 pt-4">
                                            <h5>ทะเบียนรถ : {{ $res['VehicleCode'] }}</h5>
                                            <h5>ประเภทรถ : {{ $Carsize }} </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-8 mt-md-0 mt-4">
                                <div class="row">
                                    <div class="col-sm-7 mt-2">
                                        <h5>ข้อมูลจัดส่ง</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-4">
                                                <thead style="background: #ddf5f0">
                                                    <th>หัวข้อ</th>
                                                    <th>รายละเอียด</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>เวลาแสกนเข้ารับสินค้า</td>
                                                        <td>{{ $res['JoinTime'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>เวลาแสกนออกจากคลัง</td>
                                                        <td>{{ $res['ExitTime'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>กำหนดส่ง</td>
                                                        <td>{{ $res['SentDate'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>จำนวนร้านที่ส่ง/จำนวนร้านทั้งหมด</td>
                                                        <td>{{ $res['SendSuc'] }}/{{ $res['CustAll'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>จำนวนสินค้า/จำนวนสินค้าทั้งหมด</td>
                                                        <td>{{ $res['SumSuc'] }}/{{ $res['SumItem'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ส่งร้านสุดท้ายเมื่อ</td>
                                                        <td>{{ $res['SendTime'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ระยะเวลาจัดส่งถึงร้านสุดท้าย</td>
                                                        <td>{{ $res['ExitToSend'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>เวลาส่งบิลคืนเจ้าหน้าที่</td>
                                                        <td>{{ $res['AddBill'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ระยะเวลาจากร้านสุดท้ายถึงคลัง</td>
                                                        <td>{{ $res['ExitToBill']  }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ระยะเวลารวมทั้งหมด</td>
                                                        <td>{{ $res['TimeSendAll'] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 pt-2">
                                        <h5>หมายเหตุณ์</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-condensed mb-4" style="overflow-x: auto; width: 100%;">
                                                <thead style="background: #fff5f5">
                                                    <tr>
                                                        <th>ลำดับ</th>
                                                        <th>หมายเหตุณ์</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($res['Remark']) != 0)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($res['Remark'] as $item)
                                                            <tr>
                                                                <td>{{ $i }}</td>
                                                                <td class="text-break">{{ $item->Remark }}</td>
                                                            </tr>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach
                                                    @else 
                                                    <tr>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-8 mt-md-0 mt-4">
                                @php
                                    $text = '';
                                    if(isset($res['PortAll'])){
                                        foreach ($res['PortAll'] as $key => $value) {
                                            if(auth()->user()->EmpCode !=  $value->EmpCode){
                                                $text .= ",".$value->Fullname;
                                            }
                                        }
                                    }
                                @endphp
                                <div class="row">
                                    <h5> ผู้ดูแลทั้งหมด : {{ auth()->user()->Fullname.$text }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>