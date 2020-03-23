@extends('adminlte::page')

@section('title', 'শিক্ষক তথ্য')

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
      {{ $teacher->name }}, {{ $teacher->institute->name }}, {{ $teacher->institute->upazilla->upazilla_bangla }}
      @if(Auth::user()->upazilla->contact != null)
          (<a href="tel:{{ Auth::user()->upazilla->contact }}" title="ফোন করুন (উপজেলা)" style="font-size: 0.7em">
              <i class="fa fa-phone"></i> {{ bangla(Auth::user()->upazilla->contact) }}
          </a>)
      @endif
      <div class="pull-right">
        @mobile
          @if(Auth::user()->role != 'teacher')
            <a href="{{ route('dashboard.users.edit', $teacher->id) }}" class="btn btn-primary" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i></a>
            <a href="{{ route('dashboard.leavepage', [$teacher->unique_key, $teacher->id]) }}" class="btn btn-warning" title="ছুটি প্রদান করুন"><i class="fa fa-power-off"></i></a>
          @endif
          <button type="button" onclick="location.reload();" class="btn btn-success" title="রিফ্রেশ করুন"><i class="fa fa-refresh"></i></button>
        @elsemobile
          @if(Auth::user()->role != 'teacher')
            <a href="{{ route('dashboard.users.edit', $teacher->id) }}" class="btn btn-primary" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i> সম্পাদনা</a>
            <a href="{{ route('dashboard.leavepage', [$teacher->unique_key, $teacher->id]) }}" class="btn btn-warning" title="ছুটি প্রদান করুন"><i class="fa fa-power-off"> ছুটি প্রদান</i></a>
          @endif
          <button type="button" onclick="location.reload();" class="btn btn-success" title="রিফ্রেশ করুন"><i class="fa fa-refresh"></i> রিফ্রেশ</button>
        @endmobile
      </div>
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'headmaster' || Auth::user()->id == $teacher->id)
  <div class="row">
    <div class="col-md-4">
      <big>শিক্ষক তথ্য</big>
      <div class="table-responsive">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>{{ $teacher->name }}</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>পদবিঃ {{ designation($teacher->role) }}</td></tr>
            <tr><td>ডিভাইস পিনঃ {{ $teacher->device_pin }}</td></tr>
            <tr>
              <td>
                যোগাযোগঃ <a href="tel:{{ $teacher->phone }}" title="ফোন করুন"><i class="fa fa-phone"></i> {{ $teacher->phone }}</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> {{ $teacher->name }}-এর রিপোর্ট</h3>
          <div class="box-tools pull-right"></div>
        </div>
        <div class="box-body">
          <ul class="products-list product-list-in-box">
            <li class="item">
              সময় সীমা ভিত্তিক রিপোর্ট<br>
              {{--                                <form method="POST" action="{{route('report.institute.query')}}">--}}
              {!! Form::model($teacher, ['route' => ['report.teacher.query', $teacher->unique_key], 'method' => 'POST']) !!}


              <div class="row">
                <div class="col-md-6">
                  {{--                                        <label for="query_start_date">শুরু</label>--}}
                  <input type="text" class="form-control" name="query_start_date"
                         autocomplete="off"
                         id='query_start_date' placeholder="শুরু" required>
                </div>
                <div class="col-md-6">
                  {{--                                        <label for="query_end_date">শেষ</label>--}}
                  <input type="text" class="form-control" name="query_end_date" autocomplete="off"
                         id='query_end_date' placeholder="শেষ" required>
                </div>
              </div>
              <br>
              <div class="pull-right">
                <button type="submit"
                        class="btn btn-primary btn-sm" title="সময় সীমা ভিত্তিক রিপোর্ট
 ডাউনলোড করুন">
                  <i
                          class="fa fa-download"></i> ডাউনলোড
                </button>
              </div>
              {!! Form::close() !!}
              {{--                                </form>--}}


            </li>
            <li class="item">
              মাসিক রিপোর্ট ({{ bangla(date('F, Y')) }})
              <div class="pull-right">
                <a href="{{route('report.teacher.monthly', $teacher->unique_key)}}" class="btn btn-info btn-sm" title="মাসিক রিপোর্ট ডাউনলোড করুন"><i class="fa fa-download"></i> ডাউনলোড</a>
              </div>
            </li>
            <li class="item">
              বাৎসরিক রিপোর্ট ({{ bangla(date('Y')) }})
              <div class="pull-right">
                <a href="{{route('report.teacher.yearly', $teacher->unique_key)}}" class="btn btn-warning btn-sm" title="াৎসরিক রিপোর্ট ডাউনলোড করুন"><i class="fa fa-download"></i> ডাউনলোড</a>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <big>{{ $teacher->name }} এর ছুটিসমূহ</big>
      <div class="table-responsive">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>ছুটি শুরুর তারিখ</th>
              <th>ছুটি শেষের তারিখ</th>
              <th>ছুটির কারণ</th>
            </tr>
          </thead>
          <tbody>
            @foreach($teacher->leaves as $leave)
              <tr>
                <td>{{ bangla(date('F d, Y', strtotime($leave->leave_start))) }}</td>
                <td>{{ bangla(date('F d, Y', strtotime($leave->leave_end))) }}</td>
                <td>{{ $leave->reason }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-8">
      <big>উপস্থিতি তালিকাঃ <b>{{ bangla(date('F, Y')) }}</b></big>
      <div class="table-responsive">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>তারিখ</th>
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
                  $datearray[date('F d, Y', strtotime($attendance->timestampdata))][$counter]['timestampdata'] = $attendance->timestampdata;
                  $counter++;
                }
                // echo json_encode($datearray);
              @endphp
              @foreach($datearray as $teacher)
                <tr>
                  <td>{{ bangla(date('F d, Y', strtotime(reset($teacher)['timestampdata']))) }}</td>
                  <td>{{ bangla(date('h:i A', strtotime(reset($teacher)['timestampdata']))) }}</td>
                  <td>
                    @if(reset($teacher) != end($teacher))
                      {{ bangla(date('h:i A', strtotime(end($teacher)['timestampdata']))) }}
                    @endif
                  </td>
                  <td>
                    @if(reset($teacher) != end($teacher))
                      <span class="badge badge-success">{{ bangla(Carbon::parse(end($teacher)['timestampdata'])->diffForHumans(Carbon::parse(reset($teacher)['timestampdata']))) }}</span>
                    @endif
                  </td>
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
  </script>
@endsection