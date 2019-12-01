@extends('adminlte::page')

@section('title', 'শিক্ষক তথ্য')

@section('css')

@stop

@section('content_header')
    <h1>
      {{ $teacher->name }}, {{ $teacher->institute->name }}, {{ $teacher->institute->upazilla->upazilla_bangla }}
      <div class="pull-right">
        <button type="button" onclick="location.reload();" class="btn btn-success" title="রিফ্রেশ করুন"><i class="fa fa-refresh"></i> রিফ্রেশ</button>
      </div>
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'headmaster' || Auth::user()->id == $teacher->id)
  <div class="row">
    <div class="col-md-4">
      <big>শিক্ষক তথ্য</big>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>{{ $teacher->name }}</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>পদবিঃ {{ $teacher->designation }}</td></tr>
            <tr><td>ডিভাইস পিনঃ {{ $teacher->device_pin }}</td></tr>
            <tr>
              <td>
                যোগাযোগঃ <a href="tel:{{ $teacher->phone }}" title="ফোন করুন"><i class="fa fa-phone"></i> {{ $teacher->phone }}</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-8">
      <big>উপস্থিতি তালিকাঃ <b>{{ bangla(date('F, Y')) }}</b>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>তারিখ</th>
              <th>প্রবেশ</th>
              <th>প্রস্থান</th>
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
                  <td>{{ date('F d, Y', strtotime(reset($teacher)['timestampdata'])) }}</td>
                  <td>{{ date('h:i A', strtotime(reset($teacher)['timestampdata'])) }}</td>
                  <td>
                    @if(reset($teacher) != end($teacher))
                      {{ date('h:i A', strtotime(end($teacher)['timestampdata'])) }}
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