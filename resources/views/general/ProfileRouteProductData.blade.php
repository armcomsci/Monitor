<div class="row">
    <div class="col-6">
        <button class="btn btn-success  mb-2 mr-2" style="float: right" id="AddMarket"><i class="fa-solid fa-plus"></i></button>
        <div class="table-responsive" style="height: 650px;">
            <table class="table table-bordered mb-4" id="marketZone" >
                <thead style="background: #f806e4">
                    <tr>
                       <th>ชื่อตลาด</th>
                       <th>หมายเหตุ</th>
                       <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($AreaRoute as $item)
                        <tr class="market-{{ $item->MarketID }}">
                            <td>{{ $item->MarketName }}</td>
                            <td>{{ $item->Remark }}</td>
                            <td>
                                <button type="button" class="btn btn-danger del_mark" data-id="{{ $item->MarketID }}">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-6">
        <button class="btn btn-success mb-2 mr-2" style="float: right" id="AddTrans"><i class="fa-solid fa-plus"></i></button>
        <div class="table-responsive" style="height: 650px;">
            <table class="table table-bordered mb-4" >
                <thead style="background: #0683f8b4">
                    <tr>
                       <th>ชื่อศูนย์ขนส่ง</th>
                       <th>หมายเหตุ</th>
                       <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($AreaRoute2 as $item)
                    <tr class="market-{{ $item->TranCenID }}">
                        <td>{{ $item->TranCenName }}</td>
                        <td>{{ $item->remark }}</td>
                        <td>
                            <button type="button" class="btn btn-danger del_tran" data-id="{{ $item->TranCenID }}">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>