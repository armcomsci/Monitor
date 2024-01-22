
<table class="table table-bordered mb-4" >
    <thead style="background: #ddeb60">
        <tr>
            <th>รหัสจังหวัด</th>
            <th>ชื่อจังหวัด</th>
            <th>ชื่อเขต/อำเภอ</th>
            <th>ระยะห่างจาก กทม.(กม.)</th>
            <th>หมายเหตุ</th>
        </tr>
    </thead>
    @php
        $ProvinceID = '';
        $CountTd    = '';
    @endphp
    <tbody>
        @foreach ($RegAmp as $item)
        @php
            if ($ProvinceID != $item->ProvinceID || $ProvinceID == ''){
                $td         = "<td>".$item->ProvinceName."</td>";
                $style      = "border-top: 2px solid;";
            }else{
                $td = "<td></td>";
                $style  = '';
            }
        @endphp
        <tr style="{{ $style }}">
            <td>{{ $item->AmpherID }}</td>
            {!! $td !!}
            <td>{{ $item->AmpherName }}</td>
            <td>{{ number_format($item->ApartCity,2) }}</td>
            <td>{{ $item->Remark }}</td>
            @php
                $ProvinceID = $item->ProvinceID;
            @endphp
        </tr>
        @endforeach
    </tbody>
</table>