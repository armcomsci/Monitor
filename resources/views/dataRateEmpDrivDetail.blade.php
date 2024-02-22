<div class="row">
    <div class="col-3">
        <img src="https://images.jtpackconnect.com/empdrive/{{ $empCode.".jpg" }}" class="" alt="..."  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';" style="width: 100%; height: 300px;">
    </div>
    <div class="col-9">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-condensed mb-4">
                <thead>
                    <tr>
                        <th>หัวข้อ</th>
                        <th>หัก/คะแนน</th>
                        <th>หมายเหตุ</th>
                        <th>วันที่</th>
                    </tr>
                </thead>
                @php
                    $SumScore = ['0'];
                @endphp
                <tbody>
                    @if(count($RateEmp) != 0)
                        @foreach ($RateEmp as $item)
                            @php
                                $SumScore[] = $item->scoreRate;
                            @endphp
                            <tr>
                                <td>{{ $item->subTitleName }}</td>
                                <td class="text-center">{{ $item->scoreRate }}</td>
                                <td>{{ $item->remark }}</td>
                                <td>{{ ShowDate($item->created_time,"d-m-Y H:i") }}</td>
                            </tr>
                        @endforeach
                    @else 
                    <tr>
                        <td class="text-center" colspan="4"> <h5>ไม่พบรายการ</h5></td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">คงเหลือ</td>
                        <td class="text-center">
                            {{ 100-array_sum($SumScore)  }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

