<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table class="table table-bordered mb-4" id="TableWorkLeave" border="1">
        <thead style="background: #76cedd">
            <tr>
                <th style="vertical-align: middle; text-align: center">รหัสพนักงาน</th>
                <th style="vertical-align: middle; text-align: center">ทะเบียนรถ</th>
                <th>ชื่อ-นามสกุล</th>
                @php
                    $LimitLeave = [];
                @endphp
                @foreach ($LeaveWork as $itemWork)
                    <th style="vertical-align: middle; text-align: center">{{ $itemWork->leave_name }}</th>
                    <th style="vertical-align: middle; text-align: center">ใช้ไป</th>
                    <th style="vertical-align: middle; text-align: center">คงเหลือ</th>
                    @php
                        $idLeave = $itemWork->id;
                        $LimitLeave[$idLeave]['id']      = $itemWork->id;
                        $LimitLeave[$idLeave]['name']    = $itemWork->leave_name;
                        $LimitLeave[$idLeave]['limit']   = $itemWork->leave_limit_date;
                    @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($EmpName as $item)
            <tr>
                <td style="vertical-align: middle; text-align: center" >{{ $item->EmpDriverCode }}</td>
                <td style="vertical-align: middle; text-align: center" >{{ $item->VehicleCode }}</td>
                <td>{{ $item->EmpDriverName }}</td>
                @foreach ($LeaveWork as $LeaveDay)
                    @php
                        $Day = GetLeaveWork($item->EmpDriverCode,$Month,$Year,$LeaveDay->id);
                        $LeaveDayId = $LeaveDay->id;
                    @endphp
                <td style="vertical-align: middle; text-align: center">
                    {{ $LimitLeave[$LeaveDayId]['limit'] }} วัน
                </td> 
                <td style="vertical-align: middle; text-align: center">
                    @if($Day > 0)
                       {{ ConvertLeaveStr($Day) }}
                    @else 
                        0 วัน 
                    @endif
                </td>
                <td style="vertical-align: middle; text-align: center"> 
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
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>