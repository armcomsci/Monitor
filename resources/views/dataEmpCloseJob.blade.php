<div class="row">
    <div class="col-12">
        <h4>งานที่ปิดแล้วทั้งหมด : <span id="SumJobClose">{{ count($jobClose) }}</span></h4>
        <div class="table-responsive" style="height: 650px;">
            <table class="table table-bordered mb-4" id="Table-JobClose" >
                <thead style="background: #d4fa00">
                    <tr>
                        <th>เลขตู้</th>
                        <th>คนรถ/ทะเบียน</th>
                        <th>เข้ารับสินค้า</th>
                        <th>แสกนออก</th>
                        <th>จำนวนที่ส่ง/สินค้าทั้งหมด</th>
                        <th>ร้านค้าที่ส่ง/ร้านทั้งหมด</th>
                        <th>เวลาวางบิล</th>
                        <th>ระยะเวลาเดินทางทั้งหมด</th>
                        <th>วันที่ปิดงาน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobClose as $job)
                        <tr class="showDetail" data-containerno="{{ $job->ContainerNo }}">
                            <td>#{{ $job->ContainerNo }}</td>
                            <td class="text-break">{{ $job->DriveName." ".$job->DriveTel }}<br>{{ $job->VehicleCode }}</td>
                            <td>{{ $job->JoinTime }}</td>
                            <td>{{ $job->ExitTime }}</td>
                            <td>{{ $job->SumItemSend."/".$job->SumItemAll }}</td>
                            <td>{{ $job->CustSendSuccess."/".$job->CustSendAll }}</td>
                            <td>{{ $job->AddBillTime }}</td>
                            <td>{{ $job->TimeSendAll }}</td>
                            <td>{{ ShowDate($job->Created_time,"d-m-Y H:i") }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>