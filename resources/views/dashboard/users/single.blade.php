@extends('adminlte::page')

@section('title', 'শিক্ষক তথ্য')

@section('css')

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
              দৈনিক রিপোর্ট ({{ bangla(date('F d, Y')) }})
              <div class="pull-right">
                <a href="#!" class="btn btn-success btn-sm" title="কাজ চলছে..."><i class="fa fa-download"></i> ডাউনলোড</a>
              </div>
            </li>
            <li class="item">
              মাসিক রিপোর্ট ({{ bangla(date('F, Y')) }})
              <div class="pull-right">
                <a href="#!" class="btn btn-info btn-sm" title="কাজ চলছে..."><i class="fa fa-download"></i> ডাউনলোড</a>
              </div>
            </li>
            <li class="item">
              বাৎসরিক রিপোর্ট ({{ bangla(date('Y')) }})
              <div class="pull-right">
                <a href="#!" class="btn btn-warning btn-sm" title="কাজ চলছে..."><i class="fa fa-download"></i> ডাউনলোড</a>
              </div>
            </li>
          </ul>
        </div>
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