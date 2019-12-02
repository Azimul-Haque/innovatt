@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান তথ্য')

@section('css')

@stop

@section('content_header')
    <h1>
      {{ $institute->name }}, {{ $institute->upazilla->upazilla_bangla }}
      <div class="pull-right">
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'headmaster')
          <a href="{{ route('dashboard.institute.user.create', $institute->device_id ) }}" class="btn btn-primary" title="নতুন শিক্ষক যোগ করুন"><i class="fa fa-plus"></i> শিক্ষক যোগ</a>
        @endif
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
                      $datearray[$teacher->id][$counter]['timestampdata'] = $attendance->timestampdata;
                      $datearray[$teacher->id][$counter]['name'] = $teacher->name;
                      $datearray[$teacher->id][$counter]['phone'] = $teacher->phone;
                      $counter++;
                    }
                  }
                }
              @endphp
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
                  <td>
                    @if(reset($teacher) != end($teacher))
                      {{ bangla(Carbon::parse(end($teacher)['timestampdata'])->diffForHumans(Carbon::parse(reset($teacher)['timestampdata']))) }}
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