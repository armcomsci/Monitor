
<table class="table table-bordered mb-4" >
    <thead style="background: #ddeb60">
        <tr>
            <th>รหัสจังหวัด</th>
            <th>ชื่อภาค</th>
            <th>ชื่อจังหวัด</th>
            <th>ระยะห่างจาก กทม.(กม.)</th>
            <th>หมายเหตุ</th>
        </tr>
    </thead>
    @php
        $RegionCode = '';
        $CountTd    = '';
       
    @endphp
    <tbody>
        @foreach ($RegProvince as $item)
        @php
            if ($RegionCode != $item->RegionCode || $RegionCode == ''){
                $td         = "<td>".$item->RegionName."</td>";
                $style      = "border-top: 2px solid;";
            }else{
                $td = "<td></td>";
                $style  = '';
            }
        @endphp
        <tr style="{{ $style }}">
            <td>{{ $item->ProvinceID }}</td>
            {!! $td !!}
            <td>{{ $item->ProvinceName }}</td>
            <td>{{ number_format($item->ApartBkk,2) }}</td>
            <td>{{ $item->Remark }}</td>
        </tr>
        @php
            $RegionCode = $item->RegionCode;
        @endphp
        @endforeach
    </tbody>
</table>