
<table class="table table-bordered mb-4" >
    <thead style="background: #ddeb60">
        <tr>
            <th>รหัสศูนย์</th>
            <th>ชื่อศูนย์</th>
            <th>ระยะทางจากโรงงานถึงศูนย์ขนส่ง(กม.)</th>
            <th>หมายเหตุ</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Centran as $item)
        <tr>
            <td>{{ $item->TranCenID }}</td>
            <td>{{ $item->TranCenName }}</td>
            <td>{{ number_format($item->IndexCen,2) }}</td>
            <td>{{ $item->remark }}</td>
        </tr>
        @endforeach
    </tbody>
</table>