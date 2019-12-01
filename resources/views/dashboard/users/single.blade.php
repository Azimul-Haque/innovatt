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
              <tr>
                <td>{{ date('F d, Y h:i A', strtotime($attendance->timestampdata)) }}</td>
                <td>{{ bangla(date('F d, Y h:i a', strtotime($attendance->timestampdata))) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@stop