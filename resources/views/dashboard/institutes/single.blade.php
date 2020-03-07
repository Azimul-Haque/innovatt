@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান তথ্য')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <style>
        input, label {
            display: block;
        }
    </style>
@stop

@section('content_header')
    <h1>
        {{ $institute->name }}, {{ $institute->upazilla->upazilla_bangla }}
        @if(Auth::user()->upazilla->contact != null)
            <a href="tel:{{ $institute->upazilla->contact }}" title="ফোন করুন (উপজেলা)" style="font-size: 0.7em">
                (<i class="fa fa-phone"></i> {{ bangla($institute->upazilla->contact) }})
            </a>
      @endif
        <div class="pull-right">
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'ateo' || (Auth::user()->role == 'headmaster' && Auth::user()->institute->device_id == $institute->device_id))
                <a href="{{ route('dashboard.institute.user.create', $institute->device_id ) }}" class="btn btn-primary"
                   title="নতুন শিক্ষক/ অফিস সহকারি যোগ করুন"><i class="fa fa-plus"></i> শিক্ষক/ অফিস সহকারি যোগ</a>
            @endif
            <button type="button" onclick="location.reload();" class="btn btn-success" title="রিফ্রেশ করুন"><i
                        class="fa fa-refresh"></i> রিফ্রেশ
            </button>
        </div>
    </h1>
@stop

@section('content')
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'ateo' || (Auth::user()->role == 'headmaster' && Auth::user()->institute->device_id == $institute->device_id))
        <div class="row">
            <div class="col-md-4">
                <big>শিক্ষক/ অফিস সহকারি তালিকা (মোটঃ {{ bangla($institute->users->count()) }} জন)</big>
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক/ অফিস সহকারি</th>
                            <th>পদবি</th>
                            <th width="35%">কার্যক্রম</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.user.single', $teacher->id) }}"
                                       title="বিস্তারিত দেখুন">{{ $teacher->name }}</a>
                                    <br/>
                                    <small><a href="tel:{{ $teacher->phone }}" title="ফোন করুন"><i
                                                    class="fa fa-phone"></i> {{ $teacher->phone }}</a></small>
                                </td>
                                <td>{{ designation($teacher->role) }}</td>
                                <td>
                                    <a href="{{ route('dashboard.users.edit', $teacher->id) }}" class="btn btn-success btn-sm" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i>
                                    </a>
                                    {{-- <a href="{{ route('dashboard.users.edit', $teacher->id) }}"
                                       class="btn btn-success btn-sm" title="ছুটি প্রদান"><i class="fa fa-sign-out"></i>
                                    </a> --}}
                                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteTeacherModal{{ $teacher->id }}" data-backdrop="static" title="শিক্ষক/ অফিস সহকারি ডিলেট করুন">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <!-- Delete Modal -->
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteTeacherModal{{ $teacher->id }}" role="dialog">
                                      <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                          <div class="modal-header modal-header-danger">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> শিক্ষক/ অফিস সহকারি ডিলেট</h4>
                                          </div>
                                          <div class="modal-body">
                                            আপনি কি নিশ্চিতভাবে এই শিক্ষক/ অফিস সহকারিটিকে (<b>{{ $teacher->name }}</b>) মুছে দিতে চান?<br/>
                                            <small>* এ সংক্রান্ত সকল তথ্য মুছে যাবে!</small>
                                          </div>
                                          <div class="modal-footer">
                                            {!! Form::model($teacher, ['route' => ['dashboard.user.delete', $teacher->id], 'method' => 'DELETE', 'class' => 'form-default']) !!}
                                                {!! Form::submit('ডিলেট', array('class' => 'btn btn-danger')) !!}
                                                <button type="button" class="btn btn-default" data-dismiss="modal">ফিরে যান</button>
                                            {!! Form::close() !!}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Delete Modal -->
                                    <!-- Delete Modal -->
                                    @endif

                                    <a href="{{ route('dashboard.leavepage', [$teacher->unique_key, $teacher->id]) }}" class="btn btn-warning btn-sm" title="ছুটি প্রদান করুন"><i class="fa fa-power-off"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-pie-chart"></i> প্রতিষ্ঠানের রিপোর্ট <small><b>(ডিভাইস আইডি SN: {{ $institute->device_id }})</b></small></h3>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <li class="item">
                                দৈনিক রিপোর্ট
                                {!! Form::model($institute, ['route' => ['report.institute.daily', $institute->device_id], 'method' => 'POST']) !!}


                                <div class="row">
                                    <div class="col-md-6">
                                        {{--                                        <label for="query_start_date">শুরু</label>--}}
                                        <input type="text" class="form-control" name="query_date" autocomplete="off"
                                               id='query_date' placeholder="দিন" required>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="pull-right">
                                            <button type="submit"
                                                    class="btn btn-success btn-sm"
                                                    title="দিন ভিত্তিক রিপোর্ট ডাউনলোড করুন">
                                                <i
                                                        class="fa fa-download"></i> ডাউনলোড
                                            </button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                সময় সীমা ভিত্তিক রিপোর্ট
                                <br>
                                {{--                                <form method="POST" action="{{route('report.institute.query')}}">--}}
                                {!! Form::model($institute, ['route' => ['report.institute.query', $institute->device_id], 'method' => 'POST']) !!}


                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="query_start_date"
                                               autocomplete="off"
                                               id='query_start_date' placeholder="শুরু" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="query_end_date" autocomplete="off"
                                               id='query_end_date' placeholder="শেষ" required>
                                    </div>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary btn-sm" title="সময় সীমা ভিত্তিক রিপোর্ট">
                                        <i class="fa fa-download"></i> ডাউনলোড
                                    </button>
                                </div>
                                {!! Form::close() !!}
                            </li>
                            <li class="item">
                                মাসিক রিপোর্ট ({{ bangla(date('F, Y')) }})
                                <div class="pull-right">
                                    <a href="{{ route('report.institute.monthly', $institute->device_id) }}"
                                       class="btn btn-info btn-sm" title="মাসিক রিপোর্ট ডাউনলোড করুন"><i
                                                class="fa fa-download"></i> ডাউনলোড</a>
                                </div>
                            </li>
                            <li class="item">
                                বাৎসরিক রিপোর্ট ({{ bangla(date('Y')) }})
                                <div class="pull-right">
                                    <a href="{{ route('report.institute.yearly', $institute->device_id) }}"
                                       class="btn btn-warning btn-sm" title="বাৎসরিক রিপোর্ট ডাউনলোড করুন"><i
                                                class="fa fa-download"></i> ডাউনলোড</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <big>উপস্থিতি তালিকাঃ <b>{{ bangla(date('F d, Y')) }}</b></big>
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক/ অফিস সহকারি</th>
                            <th>প্রবেশ</th>
                            <th>প্রস্থান</th>
                            <th>অবস্থানকাল</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $datearray = [];
                            $counter = 0;
                            foreach($attendances as $attendance) {
                              foreach($teachers as $teacher) {
                                if(($attendance->device_pin == $teacher->device_pin)) {
                                  $datearray[$teacher->id][$counter]['id'] = $teacher->id;
                                  $datearray[$teacher->id][$counter]['name'] = $teacher->name;
                                  $datearray[$teacher->id][$counter]['phone'] = $teacher->phone;
                                  $datearray[$teacher->id][$counter]['timestampdata'] = $attendance->timestampdata;
                                } else{
                                  // $datearray[$teacher->id][$counter]['timestampdata'] = 'absent';
                                }
                                $counter++;
                              }
                            }
                        @endphp
                        @foreach($datearray as $teacher)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.user.single', $teacher['id']) }}" title="বিস্তারিত দেখুন">
                                       {{ $teacher['name'] }}
                                    </a>
                                    <br/>
                                    <small>
                                        <a href="tel:{{ reset($teacher)['phone'] }}" title="ফোন করুন">
                                            <i class="fa fa-phone"></i> {{ reset($teacher)['phone'] }}
                                        </a>
                                    </small>
                                </td>

                                <td>{{ bangla(date('F d, Y h:i A', strtotime(reset($teacher)['timestampdata']))) }}</td>
                                <td>
                                    @if(reset($teacher) != end($teacher))
                                        {{ bangla(date('F d, Y h:i A', strtotime(end($teacher)['timestampdata']))) }}
                                    @endif
                                </td>
                                <td>
                                    @if(reset($teacher) != end($teacher))
                                        <span class="badge badge-success">{{ bangla(Carbon::parse(end($teacher)['timestampdata'])->diffForHumans(Carbon::parse(reset($teacher)['timestampdata']))) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @foreach($absents as $teacher)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.user.single', $teacher['id']) }}" title="বিস্তারিত দেখুন">
                                       {{ $teacher['name'] }}
                                    </a>
                                    <br/>
                                    <small>
                                        <a href="tel:{{ $teacher['phone'] }}" title="ফোন করুন">
                                            <i class="fa fa-phone"></i> {{$teacher['phone'] }}
                                        </a>
                                    </small>
                                </td>
                                <td>
                                    @foreach($leaves as $leave)
                                        @if($teacher['leave_start_date'] == null)
                                            অনুপস্থিত
                                        @else
                                            ছুটিতে
                                        @endif
                                    @endforeach
                                </td>
                                <td></td>
                                <td></td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                <big>চলতি ছুটিতে আছেন যারা</big>
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক/ অফিস সহকারি</th>
                            <th>ছুটি শুরুর তারিখ</th>
                            <th>ছুটি শেষের তারিখ</th>
                            <th>ছুটির কারণ</th>
                            <th>ছুটি প্রদানকারী</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->teacher->name }}</td>
                                <td>{{ bangla(date('F d, Y', strtotime($leave->leave_start))) }}</td>
                                <td>{{ bangla(date('F d, Y', strtotime($leave->leave_end))) }}</td>
                                <td>{{ $leave->reason }}</td>
                                <td>{{ $leave->issuer->name }} ({{ designation($leave->issuer->role) }})</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@stop


@section('js')
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $("#query_start_date").datepicker({
            widgetPositioning: {
                horizontal: 'right',
                vertical: 'bottom'
            },
            format: 'MM dd, yyyy',
            todayHighlight: true,
            autoclose: true,
        });
        $("#query_end_date").datepicker({
            widgetPositioning: {
                horizontal: 'right',
                vertical: 'bottom'
            },
            format: 'MM dd, yyyy',
            todayHighlight: true,
            autoclose: true,
        });
        $("#query_date").datepicker({
            widgetPositioning: {
                horizontal: 'right',
                vertical: 'bottom'
            },
            format: 'MM dd, yyyy',
            todayHighlight: true,
            autoclose: true,
        });
    </script>
@endsection