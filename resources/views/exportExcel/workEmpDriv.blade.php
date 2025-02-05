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
                        $date = date("Y-$Month-$i");
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
                        $date = date("Y-$Month-$i");
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
        @php
            $sumLeave = array();
        @endphp
        <tbody>
            @foreach ($EmpName as $item)
                <tr>
                    @php
                        $LeaveAmount_last_td = 0;
                    @endphp
                    <td style="vertical-align: middle; text-align: center">
                        {{ $item->EmpDriverCode }}
                    </td>
                    <td style="vertical-align: middle;">
                        {{ $item->EmpDriverName }}
                    </td>
                    @for ($n = 1; $n < $lastM; $n++)
                        @php
                            $LeaveAmount = 0;
                            $dateFormat = str_pad($n,2,"0",STR_PAD_LEFT);
                            $date = date("Y$Month$dateFormat");
                            $day  = date('w',strtotime($date));
                            if($day == 0){
                                continue;
                            }
                            if(!isset($sumLeave[$date])){
                                $sumLeave[$date][]  = 0;
                            }
                            $leaveWork = GetWorkEmp_Day($item->EmpDriverCode,$date);
                        @endphp
                        <td style="vertical-align: middle; text-align: center">
                            @if($leaveWork != "")
                                {{ $leaveWork->leave_name }}
                                <br>
                                @if ($leaveWork->leave_type == 'D')
                                    @php
                                        $LeaveAmount_last_td += $leaveWork->leave_amount*8;
                                        $LeaveAmount += $leaveWork->leave_amount*8;
                                    @endphp
                                    1(วัน)
                                @elseif ($leaveWork->leave_type == 'H')
                                    @php
                                        $LeaveAmount_last_td += $leaveWork->leave_amount;
                                        $LeaveAmount         += $leaveWork->leave_amount;
                                    @endphp
                                    {{ $leaveWork->leave_amount }}(ชั่วโมง)
                                @endif
                                @php
                                    $sumLeave[$date][]  = $LeaveAmount;
                                @endphp
                            @else
                                @php
                                    $sumLeave[$date][]  = 0;
                                @endphp
                            @endif
                        </td>
                    @endfor
                    <td style="vertical-align: middle;">
                        {{ ConvertLeaveStr($LeaveAmount_last_td) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        {{-- {{ dd($sumLeave) }} --}}
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right;">รวม</td>
                @for ($b = 1; $b < $lastM; $b++)
                    @php
                        $dateFormat = str_pad($b,2,"0",STR_PAD_LEFT);
                        $date = date("Y$Month$dateFormat");
                        $day  = date('w',strtotime($date));
                        if($day == 0){
                            continue;
                        }
                    @endphp
                    <td style="vertical-align: middle; text-align: center">
                        {{ ConvertLeaveStr(array_sum($sumLeave[$date])) }}
                    </td>
                @endfor
            </tr>
        </tfoot>
    </table>
</body>

</html>
