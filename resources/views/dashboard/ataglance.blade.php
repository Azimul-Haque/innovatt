@extends('adminlte::page')

@section('title', 'এক নজরে')

@section('css')
    <style>
        input, label {
            display: block;
        }
    </style>
@stop

@section('content_header')
    <h1>
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
            {{ Auth::user()->upazilla->upazilla_bangla }}, {{ Auth::user()->upazilla->district_bangla }}
        @endif
        @if(Auth::user()->upazilla->contact != null)
            <div class="pull-right" style="font-size: 0.7em">
                যোগাযোগ:
                <a href="tel:{{ Auth::user()->upazilla->contact }}" title="ফোন করুন (উপজেলা)">
                    <i class="fa fa-phone"></i> {{ bangla(Auth::user()->upazilla->contact) }}
                </a>
            </div>
        @endif
    </h1>
@stop

@section('content')
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
        @php
            $totalteachersupazilla = 0;
            foreach (Auth::user()->upazilla->institutes as $institute) {
              $totalteachersupazilla = $totalteachersupazilla + $institute->users->count();
            }
        @endphp
        <div class="row">
            <div class="col-md-6">
                <div class="box box-success" style="position: relative; left: 0px; top: 0px;">
                    <div class="box-header ui-sortable-handle" style="">
                        <i class="fa fa-bar-chart"></i>
                        <h3 class="box-title">দৈনিক উপস্থিতির তুলনা</h3>
                        <div class="box-tools pull-right text-muted">
                            {{ date('F d, Y') }}
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <canvas id="ChartJS1"></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary" style="position: relative; left: 0px; top: 0px;">
                    <div class="box-header ui-sortable-handle" style="">
                        <i class="fa fa-bar-chart"></i>
                        <h3 class="box-title">উপস্থিতি ও অনুপস্থিতির তুলনা</h3>
                        <div class="box-tools pull-right text-muted">
                            সর্বশেষ ০৭ কর্মদিবস
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <canvas id="ChartJS2"></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    @endif
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
        <script type="text/javascript">
            var ctx = document.getElementById('ChartJS1').getContext('2d');
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'bar',
                // The data for our dataset
                data: {
                    labels: ['উপস্থিত', 'অনুপস্থিত'],
                    datasets: [{
                        label: '',
                        borderColor: "#3e95cd",
                        fill: true,
                        data: [{{ $totalpresenttoday }}, {{ $totalteachersupazilla - $totalpresenttoday }}],
                        borderWidth: 2,
                        borderColor: "rgba(0,165,91,1)",
                        borderCapStyle: 'butt',
                        pointBorderColor: "rgba(0,165,91,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(0,165,91,1)",
                        pointHoverBorderColor: "rgba(0,165,91,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 5,
                        pointHitRadius: 10,
                    }]
                },
                // Configuration options go here
                options: {
                    legend: {
                        display: false
                    },
                    elements: {
                        line: {
                            tension: 0
                        }
                    }
                }
            });
        </script>
        <script>
            var ctx = document.getElementById('ChartJS2').getContext('2d');
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'line',
                // The data for our dataset
                data: {
                    labels: [
                        @foreach($totalpresentarray as $presentdata)
                            "{{ date('M d', strtotime($presentdata['date'])) }}",
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($totalpresentarray as $presentdata)
                                "{{ $presentdata['count'] }}",
                            @endforeach
                        ],
                        label: "উপস্থিত",
                        borderColor: "#3e95cd",
                        fill: false
                    }, {
                        data: [
                            @foreach($totalpresentarray as $presentdata)
                                "{{ $totalteachersupazilla - $presentdata['count'] }}",
                            @endforeach
                        ],
                        label: "অনুপস্থিত",
                        borderColor: "#DD4B39",
                        fill: false
                    }
                    ]
                },
                // Configuration options go here
                options: {
                    legend: {
                        display: true
                    },
                    elements: {
                        line: {
                            tension: 0
                        }
                    }
                }
            });
        </script>

    @endif
@endsection