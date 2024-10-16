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
    <table border="1" style="border-spacing: 0px;">
        <thead>
            <tr>
                <th colspan="3" align="center" >
                    <img src="{{ public_path()."/icon/logo.png" }}" alt="">
                </th>
                <th colspan="4"  align="center">บันทึก KPI พนักงานส่งสินค้า</th>

                <th colspan="12"  align="center">ประจำปี : {{ $Year }}</th>
            </tr>
            <tr>
                <th rowspan="2" align="middle" width="60">รหัส</th>
                <th rowspan="2" align="middle">รายชื่อ</th>
                <th rowspan="2" align="middle">ประเภทรถ</th>
                <th rowspan="2" align="middle">ทะเบียนรถ</th>
                @php
                    $strMonthCut = Array(
                                        "01" =>"ม.ค.",
                                        "02" =>"ก.พ.",
                                        "03" =>"มี.ค.",
                                        "04" =>"เม.ย.",
                                        "05" =>"พ.ค.",
                                        "06" =>"มิ.ย.",
                                        "07" =>"ก.ค.",
                                        "08" =>"ส.ค.",
                                        "09" =>"ก.ย.",
                                        "10" =>"ต.ค.",
                                        "11" =>"พ.ย.",
                                        "12" =>"ธ.ค."
                                    );
                @endphp
                @foreach ($strMonthCut as $Month)
                    <th colspan="2"  align="center">{{ $Month }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($strMonthCut as $Month)
                    <th  align="center">คะแนน</th>
                    <th  align="center">จำนวนครั้ง</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($EmpName as $item)
                @php
                    $Carsize = '';
                    $remark  = '';
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
                    <td style="width: 60px;">{{ $item->EmpDriverCode }}</td>
                    <td>{{ $item->EmpDriverName }}</td>
                    <td align="center">{{ $Carsize }}</td>
                    <td align="center">{{ $item->VehicleCode }}</td>
                    @foreach ($strMonthCut as $key => $Month)
                         @php
                             $getScore = GetScoreSumRate($item->EmpDriverCode,$key,$Year);
                         @endphp
                         @if ($getScore != "")
                         <td align="center">{{ 100-$getScore->sumScore }}</td>
                         <td align="center">{{ $getScore->countScore }}</td>
                         @else
                         <td align="center">100</td>
                         <td align="center">0</td>
                         @endif
                        
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>