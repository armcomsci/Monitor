
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
        <div class="col-8">
            <div class="table-responsive" style="height: 650px;">
                <table class="table table-bordered mb-4">
                    <tr style="background-color: #009688">
                        <th>ผู้ดูแล</th>
                        <th>เดือน</th>
                        <th class="text-center">คะแนน</th>
                    </tr>
                    @php
                        $color = array('#eaf1ff','#ddf5f0','#fff5f5','#f3effc');
                        $Month = '';
                        $Color_i = 0;
                        $SumTotal = [];
                    @endphp
                    <tbody>
                        @foreach ($ScoreSum as $score)
                        @php
                            if($Month != $score->ScoreMonth && $Month != ''){
                                $Color_i++;
                            }
                        @endphp
                        <tr style="background-color: {{ $color[$Color_i] }}">
                            <td>{{ $score->Fullname }}</td>
                            <td>{{ MonthThai(str_pad($score->ScoreMonth,2,'0',STR_PAD_LEFT)) }}</td>
                            <td>{{ number_format($score->TotalScore,2) }}</td>
                        </tr>
                        @php
                            $Month =  $score->ScoreMonth;
                        @endphp
                        @endforeach
                    </tbody>
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