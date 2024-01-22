
<table class="table table-bordered mb-4" >
    <thead style="background: #ddeb60">
       <tr>
            <th>รหัสตลาด</th>
            <th>ชื่อตลาด</th>
            <th>หมายเหตุ</th>
       </tr>
    </thead>
    <tbody>
        @foreach ($tmBkkZone as $item)
        <tr>
            <td></td>
            <td>{{ $item->SubZoneName }}</td>
            <td>{{ $item->Remark }}</td>
        </tr>
        @endforeach
    </tbody>
</table>