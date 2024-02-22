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
                <th colspan="2" align="center" >
                    <img src="{{ public_path()."/icon/logo.png" }}" alt="">
                </th>
                <th colspan="4"  align="center">บันทึก KPI พนักงานส่งสินค้า</th>
                @php
                    $Month = date('n');
                @endphp
                <th colspan="12"  align="center">ประจำเดือน : {{ getMonth($Month) }} </th>
            </tr>
            <tr>
                <th rowspan="2" align="middle" width="40">ลำดับ</th>
                <th rowspan="2" align="middle">รายชื่อ</th>
                <th rowspan="2" align="middle">ประเภทรถ</th>
                <th rowspan="2" align="middle">ทะเบียนรถ</th>
                <th rowspan="2" align="middle">จำนวน</th>
                <th rowspan="2" align="middle">คงเหลือ</th>
                @foreach ($HeaderExcel as $head)
                    <th   colspan="{{ count($head['SubHead']) }}" align="center" style="width: 200px; word-wrap: break-word; ">{{ $head['Title'] }} ({{ $head['Score'] }})</th>
                @endforeach
                <th rowspan="2"  align="center" valign="center">หมายเหตุ</th>
            </tr>
            <tr>
                @php
                    $arraySubTitle = [];
                @endphp
                @foreach ($HeaderExcel as $head)
                    @foreach ($head['SubHead'] as $SubHead)
                        <th  align="center" valign="center" style="width: 120px; word-wrap: break-word; " >{{ $SubHead['SubTitle'] }}</th>
                        @php
                            $arraySubTitle[] = $SubHead['SubId'];
                        @endphp
                    @endforeach
                @endforeach
                @php
                    array_push($arraySubTitle,0);
                @endphp
            </tr>
        </thead>
        @php
            $i = 1;
        @endphp
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
                    <td style="width: 40px;" align="center">{{ $i }}</td>
                    <td>{{ $item->EmpDriverName }}</td>
                    <td align="center">{{ $Carsize }}</td>
                    <td align="center">{{ $item->VehicleCode }}</td>
                    <td align="center">{{ $item->CountScoreRate }}</td>
                    <td align="center">{{ 100-$item->SumScoreRate }}</td>
                    @foreach ($arraySubTitle as $SubTitleItem)
                        @if($SubTitleItem != 0)
                        <td align="center" >
                            @php
                                $drivScore = GetScoreRateEmpDriv($item->EmpDriverCode,$SubTitleItem);
                            @endphp
                            @if(count($drivScore) != 0)
                                {{ $drivScore['score'] }}
                                @php
                                    if($drivScore['remark'] != ""){
                                        $remark = $drivScore['remark'].',';
                                    }
                                @endphp
                            @endif
                        </td>
                        @elseif($SubTitleItem == 0)
                        <td>{{ $remark }}</td>
                        @endif
                    @endforeach
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table>
</body>
</html>