<form id="SaveTranCenZone" action="javascript:void(0);">
    <div class="table-responsive" style="height: 700px;">
            <div class="col-3 mb-3">
                <input type="text" class="form-control" id="searchInput_trans" placeholder="Search">
            </div>
            <table class="table table-bordered mb-4" id="TransTable">
                <thead style="background: #60eb9a">
                    <tr>
                        <th>
                            <input type="checkbox" class="Check_all_market">                              
                        </th>
                        <th>ชื่อศูนย์ขนส่ง</th>
                        <th>ชื่อกลุ่มขนส่ง</th>
                        <th>ชื่อพื้นที่ขนส่ง</th>
                        <th>หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($GetData as $item)
                        <tr>
                            <td>
                                <input type="checkbox" name="TranCenID[]" value="{{ $item->TranCenID }}">
                            </td>
                            <td>{{ $item->TranCenName }}</td>
                            <td>{{ $item->ZoneName }}</td>
                            <td>{{ $item->remark }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
    <input type="hidden" id="AreaCode" name="AreaCode" value="{{ $AR_Code }}">
    <button class="btn btn-primary mt-2" type="submit">บันทึกข้อมูล</button>
</form>