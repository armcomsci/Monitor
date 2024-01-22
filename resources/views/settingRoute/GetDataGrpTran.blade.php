
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
        @foreach ($GrpTran as $item)
        <tr>
            <td>{{ $item->TranGroupID }}</td>
            <td>{{ $item->TranGroupName }}</td>
            <td>{{ number_format($item->IndexGroup,2) }}</td>
            <td>{{ $item->Remark }}</td>
        </tr>
        @endforeach
    </tbody>
</table>