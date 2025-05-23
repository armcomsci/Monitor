@php
    $url = url('/ExportRateEmpDriv')."?";
    $url2 = url('/ExportRateEmpYear')."?";
    if($Export['Month'] != ""){
        $url .= "ExMonth=".$Export['Month']; 
        $url2 .= "ExMonth=".$Export['Month']; 
    }
    if($Export['Year'] != ""){
        $url .= "&ExYear=".$Export['Year']; 
        $url2 .= "&ExYear=".$Export['Year']; 
    }
    if($Export['CarTypeCode'] != ""){
        $url .= "&ExCarTypeCode=".$Export['CarTypeCode']; 
        $url2 .= "&ExCarTypeCode=".$Export['CarTypeCode']; 
    }else{
        $url .= "&ExCarTypeCode=A"; 
        $url2 .= "&ExCarTypeCode=A"; 
    }
    if($Export['groupCode'] != ""){
        $url .= "&groupCode=".$Export['groupCode']; 
        $url2 .= "&groupCode=".$Export['groupCode']; 
    }
@endphp
<div class="d-flex ">
    {{-- <div class="mr-auto p-2" style="width: 400px;">
        <input type="text" class="form-control" id="findEmpDriv" style="width: 100%" placeholder="ค้นหาพนักงาน">
    </div> --}}
    <div class="p-2">
        <a href="{{ $url }}">
            <button class="btn btn-success mb-3"><i class="fa-solid fa-file-arrow-down"></i> ดูรายงานของรายเดือน</button>
        </a>
        <a href="{{ $url2 }}">
            <button class="btn btn-info mb-3"><i class="fa-solid fa-file-arrow-down"></i> ดูรายงานของรายปี</button>
        </a>
    </div>
</div>
<div class="table-responsive" style="height: 580px;" >
    <table class="table table-bordered mb-4" id="TableRateEmp">
        <thead style="background: #76cedd">
            <tr>
                <th>รูปภาพ</th>
                <th>ทะเบียนรถ</th>
                <th>ประเภทรถ</th>
                <th>รหัส/ชื่อ-นามสกุล</th>
                <th>เบอร์ติดต่อ</th>
                <th>คะแนน</th>
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($EmpName as $item)
                @php
                     switch ($item->CarTypeCode) {
                        case 'CT001':
                            $Carsize = 'รถเล็ก';
                            break;
                        case 'CT002':
                            $Carsize = 'รถกลาง';
                            break;
                        case 'CT003':
                            $Carsize = 'รถใหญ่';
                            break;
                    }
                @endphp
                <tr>
                   <td>
                    <img alt="avatar" src="https://images.jtpackconnect.com/empdrive/{{ $item->EmpDriverCode.".jpg" }}" class="rounded-circle hidden-list"  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';"  />
                   </td>
                   <td>{{ $item->VehicleCode }}</td>
                   <td>{{ $Carsize }}</td>
                   <td>{{ $item->EmpDriverName }}</td>
                   <td>{{ $item->EmpDriverTel }}</td>
                   <td>{{ 100-$item->SumScoreRate }}</td>
                   <td>
                        <button class="btn btn-outline-info mb-2 DetailRate" data-empcode="{{ $item->EmpDriverCode }}"><i class="fa-solid fa-circle-info"></i></button>
                   </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>