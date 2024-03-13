<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
@php
    $lastM      =  date('t')+1;
    $days       = array('อา','จ', 'อ', 'พ', 'พฤ','ศ','ส');
    $DayWork    = [];
@endphp
<body>
    <table border="1" style="border-spacing: 0px;">
        <thead>
            <tr>
                <th rowspan="3" colspan="2" style="vertical-align: middle; text-align: center">เดือน {{ getMonth($Month) }} ปี {{ $Year }} </th>         
            </tr>
            <tr>
                @for ($i = 1; $i < $lastM; $i++)
                    @php
                        $date = date("Y-m-$i");
                        $day  = date('w',strtotime($date));
                        if($day == 0){
                            continue;
                        }
                    @endphp
                    <th style="vertical-align: middle; text-align: center">{{ $days[$day] }}</th>
                @endfor
            </tr>
            <tr>
                @for ($i = 1; $i < $lastM; $i++)
                    @php
                        $date = date("Y-m-$i");
                        $day  = date('w',strtotime($date));
                        if($day == 0){
                            continue;
                        }
                    @endphp
                    <th style="vertical-align: middle; text-align: center">{{ $i  }}</th>
                @endfor
                <th rowspan="2" style="vertical-align: middle; text-align: center">รวม</th>
            </tr>
            <tr>
                <th>รหัสพนักงาน</th>
                <th>ชื่อ-นามสกุล</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($EmpName as $item)
                <tr>
                    @php
                        $LeaveAmount = 0;
                    @endphp
                    <td style="vertical-align: middle; text-align: center">
                        {{ $item->EmpDriverCode }}
                    </td>
                    <td style="vertical-align: middle;">
                        {{ $item->EmpDriverName }}
                    </td>
                    @for ($n = 1; $n < $lastM; $n++)
                        @php
                            $date = date("Y-m-$n");
                            $day  = date('w',strtotime($date));
                            if($day == 0){
                                continue;
                            }
                            $leaveWork = GetWorkEmp_Day($item->EmpDriverCode,$date);
                        @endphp
                        <td style="vertical-align: middle; text-align: center">
                            @if($leaveWork != "")
                                {{ $leaveWork->leave_name }}
                                <br>
                                @if ($leaveWork->leave_type == 'D')
                                    @php
                                        $LeaveAmount += 8;
                                    @endphp
                                    1(วัน)
                                @elseif ($leaveWork->leave_type == 'H')
                                    @php
                                        $LeaveAmount += $leaveWork->leave_amount;
                                    @endphp
                                    {{ $leaveWork->leave_amount }}(ชั่วโมง)
                                @endif
                            @endif
                        </td>
                    @endfor
                    <td style="vertical-align: middle;">
                        {{ ConvertLeaveStr($LeaveAmount) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
