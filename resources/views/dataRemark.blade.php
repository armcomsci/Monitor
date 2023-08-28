<div class="table-responsive" style="height: 650px;">
    <table class="table table-bordered mb-4" id="Table-remark">
        <thead style="background: #76cedd">
            <tr>
                <th>รหัสพนักงาน/คนรถ</th>
                <th>หมายเหตุ</th>
                <th>เวลา</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($remarks as $remark)
                <tr>
                    <td>{{ $remark->EmpDriverCode." : ".$remark->EmpDriverName }}</td>
                    <td>{{ $remark->TextAlert }}</td>
                    <td>{{ ShowDate($remark->Datetime,"d-m-Y H:i") }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>