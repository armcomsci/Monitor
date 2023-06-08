<div class="row">
    <div class="col-8">
        <h5>รอบแต่ละวัน</h5>
        <div class="table-responsive" style="height: 650px;">
            <table class="table table-bordered mb-4" id="Table-EmpRun" >
                <thead style="background: #76cedd">
                    <tr>
                        <th>ทะเบียนรถ</th>
                        <th>รหัส/คนรถ</th>
                        <th>จำนวนเที่ยวรถ</th>
                        <th>วันที่</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $SumEmp     = [];
                    @endphp
                    @if(count($EmpTran) != 0)
                        @foreach ($EmpTran as $emp)
                            @php
                                $Carsize = '';
                                switch ($emp->CarTypeCode) {
                                    case 'CT001':
                                        $Carsize = '(รถเล็ก)';
                                        break;
                                    case 'CT002':
                                        $Carsize = '(รถกลาง)';
                                        break;
                                    case 'CT003':
                                        $Carsize = '(รถใหญ่)';
                                        break;
                                }
                                $Empcode                       = $emp->EmpDriverCode;

                                $SumEmp[$Empcode]['EmpName']   = $emp->EmpDriverCode." : ".$emp->EmpDriverFullName.$Carsize;
                              
                                if(isset($SumEmp[$Empcode]['StampSum'])){
                                    $Count                          = $SumEmp[$Empcode]['StampSum'];
                                    $SumEmp[$Empcode]['StampSum']   = $Count+$emp->EmpRun;
                                }else{
                                    $SumEmp[$Empcode]['StampSum']   = $emp->EmpRun;
                                }

                               
                            @endphp
                            <tr class="DetailRun" data-stampdate="{{ $emp->Stamp_date }}" data-empcode="{{ $Empcode }}">
                                <td>{{ $emp->VehicleCode }}</td>
                                <td>{{ $emp->EmpDriverCode." : ".$emp->EmpDriverFullName.$Carsize }}</td>
                                <td>{{ $emp->EmpRun }}</td>
                                <td>
                                    <span class="badge outline-badge-success shadow-none">{{ ShowDate($emp->Stamp_date,"d-m-Y") }}</span>
                                </td>
                            </tr>
                           
                        @endforeach
                    @else 
                    <tr>
                        <td colspan="4" class="text-center"><h3>ไม่พบข้อมูล</h3></td>
                    </tr>
                    @endif
                </tbody>
            </table>
            {{-- {{ var_dump($i) }} --}}
        </div>
    </div>

    <div class="col-4">
        <h5>รวมทั้งหมดตั้งแต่วันที่ : <span id="RangeDateSum"></span></h5>
        <div class="table-responsive" style="height: 550px;">
            <table class="table table-bordered mb-4" >
                <thead style="background: #76cedd">
                    <tr>
                        <th>รหัส/คนรถ</th>
                        <th>รวม/เที่ยว</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($SumEmp as $sum)
                        <tr>
                            <td>{{ $sum['EmpName'] }}</td>
                            <td>{{ $sum['StampSum'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
