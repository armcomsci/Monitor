<style>
 table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto; /* ให้คอลัมน์ปรับขนาดตามเนื้อหา */
    }
    th, td {
        border: 2px solid #ddd;
        padding: 8px;
        text-align: center;
        white-space: normal; /* อนุญาตให้ข้อความขึ้นบรรทัดใหม่ */
        word-wrap: break-word;
        max-width: 200px; /* จำกัดขนาดของคอลัมน์ */
    }
    th {
        background-color: #f2f2f2;
    }
</style>
<table class="table table-bordered mb-4" id="TableRateEmpTitle">
    <thead style="background: #76cedd">
        <tr>
            <td rowspan="3" style="vertical-align: middle; font-weight: bold;">ทะเบียนรถ</td>
            <td rowspan="3" style="vertical-align: middle; font-weight: bold;">ชื่อ-นามสกุล</td>
            @php
                $GetTitle = GetRateTitle_Sub($Year,$TitleRate,$SubTitleRate);
            @endphp
            <td colspan="{{ count($GetTitle['SubTitle'])*2 }}" style="text-align: center; font-size:12px; font-weight: bold;">
                {{ $GetTitle['Title'][0]->Title }}
            </td>
        </tr>
        <tr>
            @foreach ($GetTitle['SubTitle'] as $item)
                <td colspan="2" style="text-align: center; font-size:10px;">
                    {{ $item->Title }}
                </td>
            @endforeach
        </tr>
        <tr>
            @foreach ($GetTitle['SubTitle'] as $item2)
                <td>
                    คงเหลือ
                </td>
                <td>
                    จำนวนครั้ง
                </td>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach ($EmpName as $item)
        <tr>
           <td>{{ $item->VehicleCode }}</td>
           <td >{{ $item->EmpDriverName }}</td>
           @foreach ($GetTitle['SubTitle'] as $item3)
                @php
                    $Count_Score = GetScore_Count_RateEmp($item->EmpDriverCode,$item3->id,$Month,$Year);
                @endphp
                <td>
                    {{ 100-$Count_Score['TotalScore'] }}
                </td>
                <td>
                    {{ $Count_Score['CountTotalScore'] }}
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>