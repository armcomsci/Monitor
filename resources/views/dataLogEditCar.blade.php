<div class="table-responsive" style="height: 650px;">
    <table class="table table-bordered mb-4" id="Table-remark">
        <thead style="background: #f89c33">
            <tr>
               <th>ลำดับที่</th>
               <th>ทะเบียนรถ</th>
               <th>รายการแก้ไข</th>
               <th>ผู้ขออนุมัติ</th>
               <th>เมื่อเวลา</th>
               <th>ผู้อนุมัติ</th>
               <th>เมื่อเวลา</th>
               <th>สถานะอนุมัติ</th>
            </tr>
        </thead>
        <tbody>
            @if (count($LogEdit) == 0)
                <tr>
                    <td colspan="8" class="text-center"><h5>ไม่พบข้อมูล</h5></td>
                </tr>
            @else
                @php
                    $no = 1;
                @endphp
                @foreach ($LogEdit as $item)
                    <tr class="showEdit">
                        <td>{{ $no }}</td>
                        <td>{{ $item->vehicleCode }}</td>
                        <td>
                            @php
                                $data = json_decode($item->data_update,true);
                                // dd($data);
                            @endphp
                            @foreach ($data as $item2)
                                {{ $item2['text']." : ".$item2['val'] }} <br>
                            @endforeach
                        </td>
                        <td>
                            {{ $item->Fullname }}
                        </td>
                        <td>
                            {{ ShowDate($item->created_time,"d-m-Y H:i") }}
                        </td>
                        @if($item->confirm_by != "")
                            <td>
                                {{ $item->ConfirmFullname }}
                            </td>
                            <td>
                                {{ ShowDate($item->ConfirmTime,"d-m-Y H:i") }}
                            </td>
                        @else 
                            <td>
                                -
                            </td>
                            <td>
                                -
                            </td>
                        @endif
                        <td>
                            @if ($item->status_confirm == 'Y')
                                อนุมัติ
                            @elseif($item->status_confirm == 'R')
                                ปฏิเศษ
                            @else
                                รอดำเนินการ
                            @endif
                        </td>
                    </tr>
                @php
                    $no++;
                @endphp
                @endforeach
            @endif
        </tbody>
    </table>
</div>