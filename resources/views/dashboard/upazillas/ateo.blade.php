@extends('adminlte::page')

@section('title', 'ড্যাশবোর্ড')

@section('css')

@stop

@section('content_header')
    <h1>

        <strong>{{ $ateo->name }}</strong>
        <br><br>
    </h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-university"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">মোট প্রতিষ্ঠান</span>
                    <span class="info-box-number">{{ bangla($institutes->count()) }} টি</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">মোট শিক্ষক</span>
                    <span class="info-box-number">
              {{ bangla($totalteachers) }} জন
        </span>
                </div>
            </div>
        </div>

        <!-- fix for small devices only -->

        <div class="clearfix visible-sm-block"></div>
        <a href="{{route('dashboard.upazillas.present.ateo', $ateo->unique_key)}}">
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
        <a href="{{route('dashboard.upazillas.absent.ateo', $ateo->unique_key)}}">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">আজকের অনুপস্থিতি</span>
                        <span class="info-box-number">{{ bangla($totalteachers - $totalpresenttoday) }} জন</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="table-responsive">
                <table class="table" id="datatable-institutes">
                    <thead>
                    <tr>
                        <th>ক্রমিক</th>
                        <th>নাম</th>
                        <th>উপজেলা</th>
                        <th>ডিভাইস আইডি (SN)</th>
                        <th>শিক্ষক সংখ্যা</th>
                        <th>কার্যক্রম</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($institutes as $institute)
                        <tr>
                            <td>{{ $institute->serial }}</td>
                            <td>
                                <a href="{{ route('dashboard.institute.single', $institute->device_id) }}">{{ $institute->name }}</a>
                            </td>
                            <td>{{ $institute->upazilla->upazilla_bangla }}
                                , {{ $institute->upazilla->district_bangla }}</td>
                            <td>{{ $institute->device_id }}</td>
                            <td>{{ bangla($institute->users->count()) }} জন</td>
                            <td>
                                <a href="{{ route('dashboard.institute.single', $institute->device_id) }}"
                                   class="btn btn-info btn-sm" title="প্রতিষ্ঠান বৃত্তান্ত দেখুন"><i
                                            class="fa fa-eye"></i> বিস্তারিত</a>
                                <a href="{{ route('dashboard.institutes.edit', $institute->id) }}"
                                   class="btn btn-success btn-sm" title="প্রতিষ্ঠান সম্পাদনা করুন"><i
                                            class="fa fa-pencil"></i> সম্পাদনা</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div>
                    {{-- {{ $institutes->links() }} --}}
                </div>
            </div>

        </div>
    </div>




@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
{{--    <script type="text/javascript">--}}
{{--        var ctx = document.getElementById('ChartJS1').getContext('2d');--}}
{{--        var chart = new Chart(ctx, {--}}
{{--            // The type of chart we want to create--}}
{{--            type: 'bar',--}}
{{--            // The data for our dataset--}}
{{--            data: {--}}
{{--                labels: ['উপস্থিত', 'অনুপস্থিত'],--}}
{{--                datasets: [{--}}
{{--                    label: '',--}}
{{--                    borderColor: "#3e95cd",--}}
{{--                    fill: true,--}}
{{--                    data: [{{ $totalpresenttoday }}, {{ $totalteachersupazilla - $totalpresenttoday }}],--}}
{{--                    borderWidth: 2,--}}
{{--                    borderColor: "rgba(0,165,91,1)",--}}
{{--                    borderCapStyle: 'butt',--}}
{{--                    pointBorderColor: "rgba(0,165,91,1)",--}}
{{--                    pointBackgroundColor: "#fff",--}}
{{--                    pointBorderWidth: 1,--}}
{{--                    pointHoverRadius: 5,--}}
{{--                    pointHoverBackgroundColor: "rgba(0,165,91,1)",--}}
{{--                    pointHoverBorderColor: "rgba(0,165,91,1)",--}}
{{--                    pointHoverBorderWidth: 2,--}}
{{--                    pointRadius: 5,--}}
{{--                    pointHitRadius: 10,--}}
{{--                }]--}}
{{--            },--}}
{{--            // Configuration options go here--}}
{{--            options: {--}}
{{--                legend: {--}}
{{--                    display: false--}}
{{--                },--}}
{{--                elements: {--}}
{{--                    line: {--}}
{{--                        tension: 0--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}

{{--        var ctx = document.getElementById('ChartJS2').getContext('2d');--}}
{{--        var chart = new Chart(ctx, {--}}
{{--            // The type of chart we want to create--}}
{{--            type: 'line',--}}
{{--            // The data for our dataset--}}
{{--            data: {--}}
{{--                labels: ['Nov 29', 'Nov 30', 'Dec 01', 'Dec 02', 'Dec 03'],--}}
{{--                datasets: [{--}}
{{--                    data: [10, 11, 8, 14, 10],--}}
{{--                    label: "উপস্থিত",--}}
{{--                    borderColor: "#3e95cd",--}}
{{--                    fill: false--}}
{{--                }, {--}}
{{--                    data: [4, 3, 6, 4, 4],--}}
{{--                    label: "অনুপস্থিত",--}}
{{--                    borderColor: "#DD4B39",--}}
{{--                    fill: false--}}
{{--                }--}}
{{--                ]--}}
{{--            },--}}
{{--            // Configuration options go here--}}
{{--            options: {--}}
{{--                legend: {--}}
{{--                    display: true--}}
{{--                },--}}
{{--                elements: {--}}
{{--                    line: {--}}
{{--                        tension: 0--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
@stop