<div class="row">
    <div class="col-3">
        <img src="https://images.jtpackconnect.com/empdrive/{{ $EmpName->EmpDriverCode.".jpg" }}" class="" alt="..."  onerror="this.onerror=null;this.src='{{ asset('theme/assets/img/90x90.jpg') }}';" style="width: 100%; height: 300px;">
    </div>
    <div class="col-6">
        <div class="table-responsive">
            <table class="table mb-4">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">
                            <h3>ข้อมูลพนักงาน</h3>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 30%;">
                            <h5>รหัสพนักงาน</h5>
                        </td>
                        <td>
                            <h5>{{ $EmpName->EmpDriverCode }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>ชื่อ-นามสกุล</h5>
                        </td>
                        <td>
                            <h5>{{ $EmpName->EmpDriverName }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>เบอร์ติดต่อ</h5>
                        </td>
                        <td>
                            <h5>{{ $EmpName->EmpDriverTel }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>ทะเบียนรถ</h5>
                        </td>
                        <td>
                            <h5>{{ $EmpName->VehicleCode }}</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-3">
        <h4 class="text-center">คะแนนปัจจุบัน</h4>
        <div class="widget-content widget-content-area">
            <div id="donut-chart" class=""></div>
        </div>
    </div>
</div>
<form id="SaveRateEmpDriv" action="javascript:void(0);" enctype="multipart/form-data">
    <div class="row mt-3">
        <div class="col-md-6 mb-4">
            <label>หัวข้อ</label>
            <select class="form-control required" id="RateTitle" name="RateTitle">
                <option value=""></option>
                @foreach ($RateTitle as $title)
                    <option value="{{ $title->id }}">{{ $title->Title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-4">
            <label>หัวข้อย่อย</label>
            <select class="form-control required" id="RateSubTitle" name="RateSubTitle">
            </select>
        </div>
        <div class="col-md-10 mb-4">
            <label>หมายเหตุ</label>
            <input type="text" class="form-control " name="RateRemark" placeholder="หมายเหตุ" >
        </div>
        <div class="col-md-2 mb-4">
            <label>จำนวนครั้ง</label>
            <input type="number" class="form-control " name="RateAmount" placeholder="ระบุจำนวน" min="1" value="1" max="10" >
        </div>
        <div class="col-md-12 mt-2 mb-2">
            <input type="file" name="imgRate" accept="image/*" >
        </div>
        <button class="btn btn-primary mt-3 ml-3" id="SaveTitle" type="submit">บันทึกข้อมูล</button>
    </div>
</form>
@php
   

    if($EmpName->SumScoreRate != ""){
        $ScoreRate    = $EmpName->SumScoreRate;
    }else{
        $ScoreRate    = 0;
    }
    $SumScoreRate = 100-$ScoreRate;
@endphp
<script>
    var ScoreSum   =  {!! $SumScoreRate !!} 

    var ScoreRate  =  {!! $ScoreRate !!} 

    var ScoreChart = [ScoreSum,ScoreRate];

    var donutChart = {
        chart: {
            height: 250,
            type: 'donut',
            toolbar: {
                show: false,
            }
        },
        colors:['#3cb412','#f40000'],
        series: ScoreChart, 
        labels: ['คงเหลือ','หัก'],
        dataLabels: {
            enabled: true, // Enable data labels
            formatter: function (val, opts) {
                return opts.w.globals.labels[opts.seriesIndex] + ": " + val; // Display the label and value
            },
            style: {
                fontSize: '12px', // Set font size
                colors: ['#FFF'] // Set color for "คงเหลือ"
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            showAlways: true,
                            show: true,
                            label : 'คะแนนทั้งหมด'
                        }
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 550,
            options: {
                chart: {
                    width: 100
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        grid: {
            padding: {
                top: 30,
                right: -60,
                bottom: 0,
                left: 0
            }
        },

    }

    var donut = new ApexCharts(
        document.querySelector("#donut-chart"),
        donutChart
    );
    donut.render();
</script>

