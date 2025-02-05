@php
    $url = url('/ExportEmpDrivWork')."?Month=".$Month."&Year=".$Year."&CarTypeCode=".$CarTypeCode."&groupCode=".$groupCode;
    $url2 = url('/ExportEmpDrivWorkAll')."?Month=".$Month."&Year=".$Year."&CarTypeCode=".$CarTypeCode."&groupCode=".$groupCode;
@endphp
<div class="d-flex ">
    <div class="p-2">
        <a href="{{ $url }}">
            <button class="btn btn-success mb-3"><i class="fa-solid fa-file-arrow-down"></i> Export Excel แบบรายเดือน</button>
        </a>
    </div>
    <div class="p-2">
        <a href="{{ $url2 }}">
            <button class="btn btn-info mb-3"><i class="fa-solid fa-file-arrow-down"></i> Export Excel สรุปคงเหลือ</button>
        </a>
    </div>
</div>
<div class="table-responsive" style="height: 500px;" >
    <table class="table table-bordered mb-4" id="TableWorkLeave" border="1">
        <thead style="background: #76cedd">
            <tr>
                <th>รูปภาพ</th>
                <th>รหัสพนักงาน</th>
                <th>ทะเบียนรถ</th>
                <th>ชื่อ-นามสกุล</th>
                <th>ประเภทรถ</th>
                @php
                    $LimitLeave = [];
                @endphp
                @foreach ($LeaveWork as $itemWork)
                    <th>{{ $itemWork->leave_name }}</th>
                    <th>คงเหลือ</th>
                    @php
                        $idLeave = $itemWork->id;
                        $LimitLeave[$idLeave]['id']      = $itemWork->id;
                        $LimitLeave[$idLeave]['name']    = $itemWork->leave_name;
                        $LimitLeave[$idLeave]['limit']   = $itemWork->leave_limit_date;
                    @endphp
                @endforeach
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($EmpName as $item)
                @php
                    switch ($item->CarTypeCode) {
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
            <tr>
                <td>
                <img alt="avatar" src="https://images.jtpackconnect.com/empdrive/{{ $item->EmpDriverCode.".jpg" }}" class="rounded-circle hidden-list"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"  />
                </td>
                <td>{{ $item->EmpDriverCode }}</td>
                <td>{{ $item->VehicleCode }}</td>
                <td>{{ $item->EmpDriverName }}</td>
                <td>{{ $Carsize }}</td>
                @foreach ($LeaveWork as $LeaveDay)
                    @php
                        $Day = GetLeaveWork($item->EmpDriverCode,$Month,$Year,$LeaveDay->id);
                        $LeaveDayId = $LeaveDay->id;
                    @endphp
                <td>
                    {{ $LimitLeave[$LeaveDayId]['limit'] }} วัน
                </td> 
                <td> 
                @php
                    $Work_leave = ($LimitLeave[$LeaveDayId]['limit']*8)-$Day;
                    if($Day > 0){
                       echo  ConvertLeaveStr($Work_leave);
                    }else{
                        echo $LimitLeave[$LeaveDayId]['limit']." วัน";
                    }
                @endphp
                </td>
                @endforeach
                <td>
                    <button class="btn btn-outline-info mb-2 DetailWorkLeave" data-empcode="{{ $item->EmpDriverCode }}"><i class="fa-solid fa-circle-info"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>