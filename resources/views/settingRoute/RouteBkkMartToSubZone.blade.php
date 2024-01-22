
<table class="table table-bordered mb-4" >
    <thead style="background: #ddeb60">
        <tr>
            <th>รหัสโซนหลัก</th>
            <th>ชื่อโซนหลัก</th>
            <th>หมายเหตุ</th>
            <th>ลำดับ</th>
            <th>รหัสโซนย่อย</th>
            <th>ชื่อโซนย่อย</th>
            <th>หมายเหตุโซนย่อย</th>
        </tr>
    </thead>
    @php
        $MainZoneID = '';
    @endphp
    <tbody>
        @foreach ($MainToSubZone as $item)
            @php
                if ($MainZoneID != $item->MainZoneID || $MainZoneID == ''){
                    $td         = "<td>".$item->MainZoneName."</td>"." <td>".$item->RemarkMain."</td>";
                    $style      = "border-top: 2px solid;";
                }else{
                    $td = "<td></td><td></td>";
                    $style  = '';
                }
            @endphp
             <tr style="{{ $style }}">
                <td></td>
                {!! $td !!}
               
                <td>{{ $item->Priority }}</td>
                <td></td>
                <td>{{ $item->SubZoneName }}</td>
                <td>{{ $item->Remark }}</td>
            </tr>
            @php
                $MainZoneID = $item->MainZoneID;
            @endphp
        @endforeach
    </tbody>
</table>