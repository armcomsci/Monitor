
<div class="row">
        <div class="col-4">
            <div id="chartDonut" class="col-xl-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>ภาพรวม</h4> 
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div id="donut-chart" class=""></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="table-responsive" style="height: 650px;">
                <table class="table table-bordered mb-4">
                    <tr style="background-color: #009688">
                        <th>ผู้ดูแล</th>
                        <th>เดือน</th>
                        <th class="text-center">คะแนนที่ปิดงานคนเดียว</th>
                        <th class="text-center">คะแนนปิดงานที่มีผู้ดูแลมากกว่า 1 ท่าน</th>
                        <th class="text-center">คะแนนรวม</th>
                    </tr>
                    @php
                        $color = array('#eaf1ff','#ddf5f0','#fff5f5','#f3effc');
                        $Month = '';
                        $Color_i = 0;
                        $SumTotal = [];
                        $SumCloseOneAll = [];
                        $SumtransferAll = [];
                        $TotalScoreAll  = [];
                    @endphp
                    <tbody>
                        @foreach ($ScoreSum as $score)
                        @php
                            $StrMonth = str_pad($score->ScoreMonth,2,'0',STR_PAD_LEFT);

                            array_push($SumCloseOneAll,$score->sum_transfer);
                            array_push($SumtransferAll,$score->SumCloseOne);
                            array_push($TotalScoreAll,$score->TotalScore);

                            // $SumCloseOneAll[] = $score->sum_transfer;
                            // $SumtransferAll[] = $score->SumCloseOne;
                            // $TotalScoreAll[]  = $score->TotalScore;

                            if($Month != $score->ScoreMonth && $Month != ''){
                                $Color_i++;
                            }

                            if(!isset($SumTotal[$StrMonth])){
                                $SumTotal[$StrMonth] = $score->TotalScore;
                            }else{
                                $SumTotal[$StrMonth] += $score->TotalScore;
                            }
                           
                            if($Color_i%3 == 0){
                                $Color_i  = 0;
                            }
                           
                        @endphp
                        <tr style="background-color: {{ $color[$Color_i] }}">
                            <td>{{ $score->Fullname }}</td>
                            <td>{{ MonthThai($StrMonth) }}</td>
                            <td class="text-center">{{ number_format($score->sum_transfer,2) }}<br>คิดเป็น {{ sumToPercent($score->sum_transfer,$score->TotalScore) }}%</td>
                            <td class="text-center">{{ number_format($score->SumCloseOne,2) }}<br>คิดเป็น {{ sumToPercent($score->SumCloseOne,$score->TotalScore) }}%</td>
                            <td class="text-center">{{ number_format($score->TotalScore,2) }}</td>
                        </tr>
                        @php
                            $Month =  $score->ScoreMonth;
                        @endphp
                        @endforeach
                    </tbody>

                    <tfoot>
                        @php
                            $SumCloseOneAll =  array_sum($SumCloseOneAll);
                            $SumtransferAll =  array_sum($SumtransferAll);
                            $TotalScoreAll =  array_sum($TotalScoreAll);
                        @endphp
                        <tr>
                            <td colspan="2" class="text-right">รวม</td>
                            <td class="text-center">{{  number_format($SumCloseOneAll,2) }}<br>คิดเป็น {{ sumToPercent($SumCloseOneAll,$TotalScoreAll) }}%</td>
                            <td class="text-center">{{  number_format($SumtransferAll,2) }}<br>คิดเป็น {{ sumToPercent($SumtransferAll,$TotalScoreAll) }}%</td>
                            <td class="text-center">{{  number_format($TotalScoreAll,2) }}<br></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-3">
            <div class="table-responsive" style="height: 650px;">
                @php
                      $strMonthCut = Array("01"=>"มกราคม"
                                ,"02"=>"กุมภาพันธ์"
                                ,"03"=>"มีนาคม"
                                ,"04"=>"เมษายน"
                                ,"05"=>"พฤษภาคม"
                                ,"06"=>"มิถุนายน"
                                ,"07"=>"กรกฎาคม"
                                ,"08"=>"สิงหาคม"
                                ,"09"=>"กันยายน"
                                ,"10"=>"ตุลาคม"
                                ,"11"=>"พฤจิกายน"
                                ,"12"=>"ธันวาคม");
                @endphp
                <table class="table table-bordered mb-4">
                    <tr style="background-color: #f5b500">
                        <th>เดือน</th>
                        <th class="text-center">คะแนนรวม</th>
                    </tr>

                    @foreach ($strMonthCut as $key => $Month)
                        <tr>
                            <td>{{ $Month }}</td>
                            <td>
                                @if(!isset($SumTotal[$key]))
                                    0
                                @else
                                    {{ number_format($SumTotal[$key],2) }}
                                    @php
                                        $SumScore[] = $SumTotal[$key];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td><h5>รวม</h5></td>
                        <td><h5>{{ number_format(array_sum($SumScore),2) }}</h5></td>
                    </tr>
                </table>
            </div>
        </div>
</div>
@php
    $ScoreChart = [];
    $NameChart  = [];
    foreach ($ScoreAll as $key => $value) {
        $ScoreChart[]  = round($value->TotalScore,2);
        $NameChart[]  = $value->Fullname;
    }
    $ScoreChart = json_encode($ScoreChart,true);
    $NameChart  = json_encode($NameChart,true);
@endphp
<script src="{{ asset('theme/plugins/apex/apexcharts.min.js') }}"></script>
<script>
    var ScoreChart = {!! $ScoreChart !!}
    var NameChart  = {!! $NameChart !!}

    var donutChart = {
        chart: {
            height: 350,
            type: 'donut',
            toolbar: {
            show: false,
            }
        },
        series: ScoreChart,
        labels : NameChart,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    }

    var donut = new ApexCharts(
        document.querySelector("#donut-chart"),
        donutChart
    );

    donut.render();
</script>