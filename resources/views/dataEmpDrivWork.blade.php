<div class="row">
    <div class="col-3">
        <img src="https://images.jtpackconnect.com/empdrive/{{ $empCode.".jpg" }}" class="" alt="..."  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';" style="width: 100%; height: 300px;">
    </div>
    <div class="col-9">
        <div class="table-responsive" style="overflow: scroll; max-height: 450px;">
            <table class="table table-bordered table-hover table-condensed mb-4">
                <thead>
                    <tr>
                        <th>วันที่เริ่มลา ถึง วันที่</th>
                        <th>จำนวน</th>
                        <th>วัน/ชั่วโมง</th>
                        <th>หมายเหตุ</th>
                        <th>ประเภทการลา</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($leave) != 0)
                        @foreach ($leave as $item)
                            <tr>
                                <td>{{ ShowDate($item->leave_date_start,"d-m-Y")." ถึง ".ShowDate($item->leave_date_end,"d-m-Y") }}</td>
                                <td>{{ number_format($item->leave_amount,0) }}</td>
                                <td>
                                    @if ($item->leave_type == 'D')
                                    วัน
                                    @elseif ($item->leave_type == 'H')
                                    ชั่วโมง
                                    @endif
                                </td>
                                <td>{{ $item->leave_remark }}</td>
                                <td>{{ $item->leave_name }}</td>
                                <td>
                                    <button class="btn btn-outline-danger mb-2 deleteWorkEmp" data-workid="{{ $item->id }}" ><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @else 
                    <tr>
                        <td class="text-center" colspan="6"> <h5>ไม่พบรายการ</h5></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

