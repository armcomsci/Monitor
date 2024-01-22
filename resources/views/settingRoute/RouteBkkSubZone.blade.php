
<table class="table table-bordered mb-4" >
    <thead style="background: #ddeb60">
        <tr>
            <th>รหัสโซนย่อย</th>
            <th>ชื่อโซนย่อย</th>
            <th>หมายเหตุ</th>
            <th>ลำดับ</th>
            <th>รหัสโซนใกล้เคียง</th>
            <th>ชื่อโซนใกล้เคียง</th>
            <th>หมายเหตุโซนใกล้เคียง</th>
        </tr>
    </thead>
    @php
        $SubZoneID = '';
        $i = 1;
    @endphp
    <tbody>
        @foreach ($bkkZone as $item)
        @php
            if ($SubZoneID != $item->SubZoneID || $SubZoneID == ''){
                $td         = "<td>".$item->SubZoneName."</td>"." <td>".$item->Remark1."</td>";
                $style      = "border-top: 2px solid;";
            }else{
                $td = "<td></td><td></td>";
                $style  = '';
            }
        @endphp
        <tr>
            <td></td>
            {!! $td !!}
           
            <td>{{ $i }}</td>
            <td></td>
            <td>{{ $item->SubZoneName2 }}</td>
            <td>{{ $item->Remark }}</td>
        </tr>
        @php
            $SubZoneID = $item->SubZoneID;
            $i++;
        @endphp
        @endforeach
    </tbody>
</table>