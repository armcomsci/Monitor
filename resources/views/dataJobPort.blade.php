<div class="table-responsive" style="height: 650px;">
    <table class="table table-bordered mb-4">
        <thead style="background: #76cedd">
            <tr>
                <th>ทะเบียนรถ/เลขตู้</th>
                <th>คนรถ/เบอร์โทร</th>
                <th>สถานะตู้</th>
                <th class="text-center">ผู้ดูแล</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Container as $emp)
                @if ($emp->status_transfer == '' || $emp->status_transfer == 'Y')
                    @php
                        $Carsize = '';
                        switch ($emp->CarType) {
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
                    <tr class="dataContain" data-contain="{{ $emp->ContainerNo }}" id="containNo-{{ $emp->ContainerNo }}">
                        {{-- <td>{{ $emp->ContainerNo }}</td> --}}
                        <td class="text-break">
                            {{ $emp->VehicleCode }}({{ $Carsize }})
                        <br>{{ $emp->ContainerNo }}
                        </td>
                        <td class="text-break">
                            <div class="avatar">
                                <img alt="avatar" src="{{ asset('theme/assets/img/90x90.jpg') }}" class="rounded-circle" />
                            </div>
                            <div>
                                {{ $emp->EmpDriverName." ".$emp->EmpDriverlastName }}<br>{{ $emp->EmpDriverTel }}
                            </div>
                        </td>
                        <td class="text-break">
                            @if(empty($emp->flag_job) && empty($emp->flag_exit))
                                <span class="badge outline-badge-danger shadow-none">ยังไม่รับงาน</span>
                            @elseif(empty($emp->flag_exit) && $emp->flag_job == 'Y')
                                <span class="badge outline-badge-success shadow-none">รับงาน</span>
                            @elseif(empty($emp->flag_exit) && $emp->flag_job == 'N') 
                                <span class="badge outline-badge-danger shadow-none">ปฏิเศษงาน</span>
                            @elseif($emp->flag_exit == "Y" && $emp->flag_job == "Y")
                                <span class="badge outline-badge-success shadow-none">แสกนรับ : {{ ShowDate($emp->created_at,"d-m-Y H:i") }}</span>
                            @elseif($emp->flag_exit == "N" && $emp->flag_job == "Y") 
                                <span class="badge outline-badge-danger shadow-none">ออกงาน: {{ ShowDate($emp->updated_at,"d-m-Y H:i") }}</span>
                            @endif
                        </td>
                        <td>{{ $emp->Fullname }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>