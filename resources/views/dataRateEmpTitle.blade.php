@php
    $url = url('/ExportRateEmpTitle')."?";
    if($Export['Month'] != ""){
        $url .= "ExMonth=".$Export['Month']; 
    }
    if($Export['Year'] != ""){
        $url .= "&ExYear=".$Export['Year']; 
    }
    if($Export['CarTypeCode'] != ""){
        $url .= "&ExCarTypeCode=".$Export['CarTypeCode']; 
    }
    if($Export['groupCode'] != ""){
        $url .= "&groupCode=".$Export['groupCode']; 
    }
    if(isset($Export['TitleRate'])){
        $TitleJson = json_encode($Export['TitleRate'],true);
        $TitleJson = base64_encode($TitleJson);

        $url .= "&TitleRate=".$TitleJson; 
    }
    if(isset($Export['SubTitleRate'])){
        $SubTitleJson = json_encode($Export['SubTitleRate'],true);
        $SubTitleJson = base64_encode($SubTitleJson);

        $url .= "&SubTitleRate=".$SubTitleJson; 
    }

@endphp
<div class="d-flex ">
    {{-- <div class="mr-auto p-2" style="width: 400px;">
        <input type="text" class="form-control" id="findEmpDriv" style="width: 100%" placeholder="ค้นหาพนักงาน">
    </div> --}}
    <div class="p-2">
        <a href="{{ $url }}">
            <button class="btn btn-success mb-3"><i class="fa-solid fa-file-arrow-down"></i> Export Excel</button>
        </a>
    </div>
</div>
<div class="table-responsive" style="height: 580px;" >
    <table class="table table-bordered mb-4" id="TableRateEmpTitle">
        <thead style="background: #76cedd">
            <tr>
                <td rowspan="3" style="vertical-align: middle;">ทะเบียนรถ</td>
                <td rowspan="3" style="vertical-align: middle;">ชื่อ-นามสกุล</td>
                @php
                    $GetTitle = GetRateTitle_Sub($Year,$TitleRate,$SubTitleRate);
                @endphp
                <td colspan="{{ count($GetTitle['SubTitle'])*2 }}" class="text-center">
                    {{ $GetTitle['Title'][0]->Title }}
                </td>
            </tr>
            <tr>
                @foreach ($GetTitle['SubTitle'] as $item)
                    <td colspan="2" class="text-center">
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
               <td style="width: 15%">{{ $item->EmpDriverName }}</td>
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
</div>