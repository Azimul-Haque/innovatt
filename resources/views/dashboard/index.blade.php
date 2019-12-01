@extends('adminlte::page')

@section('title', 'ড্যাশবোর্ড')

@section('css')

@stop

@section('content_header')
    <h1>
      @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
      {{ Auth::user()->upazilla->upazilla_bangla }}, {{ Auth::user()->upazilla->district_bangla }}
      @elseif(Auth::user()->role == 'headmaster' || Auth::user()->role == 'teacher')
        {{ Auth::user()->institute->name }}, {{ Auth::user()->upazilla->upazilla_bangla }}, {{ Auth::user()->upazilla->district_bangla }}
        <div class="pull-right">
          <button type="button" onclick="location.reload();" class="btn btn-success" title="রিফ্রেশ করুন"><i class="fa fa-refresh"></i> রিফ্রেশ</button>
        </div>
      @endif
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-university"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">মোট প্রতিষ্ঠান</span>
          <span class="info-box-number">{{ bangla(Auth::user()->upazilla->institutes->count()) }} টি</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">মোট শিক্ষক</span>
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

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-check-square-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">আজকের উপস্থিতি</span>
          <span class="info-box-number">২৪৬ জন</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">আজকের অনুপস্থিতি</span>
          <span class="info-box-number">১২ জন</span>
        </div>
      </div>
    </div>
  </div>
  @elseif(Auth::user()->role == 'headmaster')
    <div class="row">
      <div class="col-md-4">
        <big>শিক্ষক তালিকা (মোটঃ {{ bangla(Auth::user()->institute->users->count()) }} জন)</big>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>শিক্ষক</th>
                <th>পদবি</th>
              </tr>
            </thead>
            <tbody>
              @php
                $datearray = [];
                $counter = 0;
                foreach($attendances as $attendance) {
                  foreach(Auth::user()->institute->users as $teacher) {
                    if(($attendance->device_pin == $teacher->device_pin)) {
                      $datearray[$teacher->id][$counter]['timestampdata'] = $attendance->timestampdata;
                      $datearray[$teacher->id][$counter]['name'] = $teacher->name;
                      $datearray[$teacher->id][$counter]['phone'] = $teacher->phone;
                      $counter++;
                    }
                  }
                }
              @endphp
              @foreach(Auth::user()->institute->users as $teacher)
                <tr>
                  <td>
                    <a href="{{ route('dashboard.user.single', $teacher->id) }}" title="বিস্তারিত দেখুন">{{ $teacher->name }}</a>
                    <br/>
                    <small><a href="tel:{{ $teacher->phone }}" title="ফোন করুন"><i class="fa fa-phone"></i> {{ $teacher->phone }}</a></small>
                  </td>
                  <td>{{ $teacher->designation }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-8">
        <big>উপস্থিতি তালিকাঃ <b>{{ bangla(date('F d, Y')) }}</b>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>শিক্ষক</th>
                <th>প্রবেশ</th>
                <th>প্রস্থান</th>
              </tr>
            </thead>
            <tbody>
              @foreach($datearray as $teacher)
                <tr>
                  <td>
                    {{ reset($teacher)['name'] }}<br/><small><a href="tel:{{ reset($teacher)['phone'] }}" title="ফোন করুন"><i class="fa fa-phone"></i> {{ reset($teacher)['phone'] }}</a></small>
                  </td>
                  <td>{{ date('F d, Y h:i A', strtotime(reset($teacher)['timestampdata'])) }}</td>
                  <td>
                    @if(reset($teacher) != end($teacher))
                      {{ date('F d, Y h:i A', strtotime(end($teacher)['timestampdata'])) }}
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @elseif(Auth::user()->role == 'teacher')
    <div class="row">
      <div class="col-md-4">
        <big>শিক্ষক তথ্য</big>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>{{ Auth::user()->name }}</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>পদবিঃ {{ Auth::user()->designation }}</td></tr>
              <tr><td>ডিভাইস পিনঃ {{ Auth::user()->device_pin }}</td></tr>
              <tr>
                <td>
                  যোগাযোগঃ <a href="tel:{{ Auth::user()->phone }}" title="ফোন করুন"><i class="fa fa-phone"></i> {{ Auth::user()->phone }}</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-8">
        <big>উপস্থিতি তালিকা
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>প্রবেশ</th>
                <th>প্রস্থান</th>
              </tr>
            </thead>
            <tbody>
              @foreach($attendances as $attendance)
                @if($attendance->device_pin == Auth::user()->device_pin)
                  <tr>
                    <td>{{ date('F d, Y h:i A', strtotime($attendance->timestampdata)) }}</td>
                    <td>{{ bangla(date('F d, Y h:i a', strtotime($attendance->timestampdata))) }}</td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@stop