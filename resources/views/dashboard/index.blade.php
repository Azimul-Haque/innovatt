@extends('adminlte::page')

@section('title', 'ড্যাশবোর্ড')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
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
        <div class="row">
            <a href="{{route('dashboard.institutes')}}">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-university"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">মোট প্রতিষ্ঠান</span>
                            <span class="info-box-number">{{ bangla(Auth::user()->upazilla->institutes->count()) }} টি</span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{route('dashboard.institute.teachers')}}">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">মোট শিক্ষক/ অফিস সহকারি</span>
                            <span class="info-box-number">
                                @php
                                    $totalteachersupazilla = 0;
                                    foreach (Auth::user()->upazilla->institutes as $institute) {
                                      $totalteachersupazilla = $totalteachersupazilla + $institute->users->count();
                                    }
                                @endphp
                                {{ bangla($totalteachersupazilla) }} জন
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- fix for small devices only -->

            <div class="clearfix visible-sm-block"></div>
            <a href="{{route('dashboard.upazillas.present')}}">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-check-square-o"></i></span>


                        <div class="info-box-content">
                            <span class="info-box-text">আজকের উপস্থিতি</span>
                            <span class="info-box-number">{{ bangla($totalpresenttoday) }} জন</span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{route('dashboard.upazillas.absent')}}">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">আজকের অনুপস্থিতি</span>
                            <span class="info-box-number">{{ bangla($totalteachersupazilla - $totalpresenttoday) }} জন</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="row">
            <a href="{{route('dashboard.institute.teacher.female')}}">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-female"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">মোট মহিলা শিক্ষক/ অফিস সহকারি</span>
                            <span class="info-box-number">
                @php
                    $totalfemalesupazilla = 0;
                    foreach (Auth::user()->upazilla->institutes as $institute) {
                      foreach ($institute->users as $user) {
                        if($user->gender == 2) {
                          $totalfemalesupazilla++;
                        }
                      }
                    }
                @endphp
                                {{ bangla($totalfemalesupazilla) }} জন
            </span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{route('dashboard.institute.teacher.male')}}">
            <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-male"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">মোট পুরুষ শিক্ষক/ অফিস সহকারি</span>
                            <span class="info-box-number">
                @php
                    $totalmalesupazilla = 0;
                    foreach (Auth::user()->upazilla->institutes as $institute) {
                      foreach ($institute->users as $user) {
                        if($user->gender == 1) {
                          $totalmalesupazilla++;
                        }
                      }
                    }
                @endphp
                                {{ bangla($totalmalesupazilla) }} জন
            </span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <a href="{{route('dashboard.institute.teachers.late')}}">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-clock-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">বিলম্বিত প্রবেশ</span>
                            <span class="info-box-number">{{ bangla($totallateentrytoday) }} জন</span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{route('dashboard.institute.teachers.early')}}">
            <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-exclamation-triangle"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">পূর্বে প্রস্থান</span>
                            <span class="info-box-number">{{ bangla($totalearlyleavetoday) }} জন</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="box box-info" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" style="">
                <i class="fa fa-university"></i>
                <h3 class="box-title">উপজেলা তথ্য</h3>
            </div>
            <div class="box-body">
                {!! Form::open(['route' => ['dashboard.upazilla.update', Auth::user()->upazilla->id], 'method' => 'POST', 'class' => 'form-inline']) !!}
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label for="contact">প্রতিষ্ঠানে প্রবেশের সময়</label>
                        <input type="text" id="entrance" name="entrance" class="form-control" value="{{ date('h:i A', strtotime(Auth::user()->upazilla->entrance)) }}" required="">
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label for="contact">প্রতিষ্ঠানে ত্যাগের সময়</label>
                        <input type="text" id="departure" name="departure" class="form-control" value="{{ date('h:i A', strtotime(Auth::user()->upazilla->departure)) }}" required="">
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label for="contact">যোগাযোগ নম্বর</label>
                        <input type="number" id="contact" name="contact" class="form-control" @if(Auth::user()->upazilla->contact != null) value="{{ Auth::user()->upazilla->contact }}" @endif required="">
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label for="contact" style="color: #ffffff;">.</label>
                        <button type="submit" class="btn btn-primary btn-block">দাখিল করুন</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div><br/>
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
                            {{ date('F, Y') }}
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
        $("#entrance").datetimepicker({
            format: 'LT',
        });
        $("#departure").datetimepicker({
            format: 'LT',
        });
    </script>

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
                    labels: ['Nov 29', 'Nov 30', 'Dec 01', 'Dec 02', 'Dec 03'],
                    datasets: [{
                        data: [10, 11, 8, 14, 10],
                        label: "উপস্থিত",
                        borderColor: "#3e95cd",
                        fill: false
                    }, {
                        data: [4, 3, 6, 4, 4],
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