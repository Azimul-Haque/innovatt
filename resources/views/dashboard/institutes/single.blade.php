@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান তথ্য')

@section('css')

@stop

@section('content_header')
    <h1>
      {{ $institute->name }}, {{ $institute->upazilla->upazilla_bangla }}
      <div class="pull-right">
        <button type="button" onclick="location.reload();" class="btn btn-success" title="রিফ্রেশ করুন"><i class="fa fa-refresh"></i> রিফ্রেশ</button>
      </div>
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'headmaster')
    <div class="row">
      <div class="col-md-4">
        <big>শিক্ষক তালিকা (মোটঃ {{ bangla($institute->users->count()) }} জন)</big>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>শিক্ষক</th>
                <th>পদবি</th>
              </tr>
            </thead>
            <tbody>
              @foreach($institute->users as $teacher)
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
              @php
                $datearray = [];
                $counter = 0;
                foreach($attendances as $attendance) {
                  foreach($teachers as $teacher) {
                    if($attendance->device_pin == $teacher->device_pin) {
                      $datearray[$teacher->phone][$counter]['device_pin'] = $attendance->device_pin;
                      $datearray[$teacher->phone][$counter]['timestampdata'] = $attendance->timestampdata;
                      $counter++;
                    }
                  }
                }
                echo json_encode($datearray);
              @endphp
              @foreach($attendances as $attendance)
                @foreach($teachers as $teacher)
                  @if($attendance->device_pin == $teacher->device_pin)
                    <tr>
                      <td>{{ $teacher->name }}<br/><small><a href="tel:{{ $teacher->phone }}" title="ফোন করুন"><i class="fa fa-phone"></i> {{ $teacher->phone }}</a></small></td>
                      <td>{{ date('F d, Y h:i A', strtotime($attendance->timestampdata)) }}</td>
                      <td>{{ bangla(date('F d, Y h:i a', strtotime($attendance->timestampdata))) }}</td>
                    </tr>
                  @endif
                @endforeach
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@stop