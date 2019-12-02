@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান তথ্য')

@section('css')

@stop

@section('content_header')
    <h1>
      {{ $institute->name }}, {{ $institute->upazilla->upazilla_bangla }}
      <div class="pull-right">
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || (Auth::user()->role == 'headmaster' && Auth::user()->institute->device_id == $institute->device_id))
          <a href="{{ route('dashboard.institute.user.create', $institute->device_id ) }}" class="btn btn-primary" title="নতুন শিক্ষক যোগ করুন"><i class="fa fa-plus"></i> শিক্ষক যোগ</a>
        @endif
        <button type="button" onclick="location.reload();" class="btn btn-success" title="রিফ্রেশ করুন"><i class="fa fa-refresh"></i> রিফ্রেশ</button>
      </div>
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || (Auth::user()->role == 'headmaster' && Auth::user()->institute->device_id == $institute->device_id))
    <div class="row">
      <div class="col-md-4">
        <big>শিক্ষক তালিকা (মোটঃ {{ bangla($institute->users->count()) }} জন)</big>
        <div class="table-responsive">
          <table class="table table-condensed">
            <thead>
              <tr>
                <th>শিক্ষক</th>
                <th>পদবি</th>
                <th width="20%">কার্যক্রম</th>
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
                  <td>
                    <a href="{{ route('dashboard.users.edit', $teacher->id) }}" class="btn btn-success btn-sm" title="সম্পাদনা করুন"><i class="fa fa-pencil"></i> সম্পাদনা</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-pie-chart"></i> প্রতিষ্ঠানের রিপোর্ট</h3>
            <div class="box-tools pull-right"></div>
          </div>
          <div class="box-body">
            <ul class="products-list product-list-in-box">
              <li class="item">
                দৈনিক রিপোর্ট ({{ bangla(date('F d, Y')) }})
                <div class="pull-right">
                  <a href="{{ route('report.institute.daily', $institute->device_id) }}" class="btn btn-success btn-sm"><i class="fa fa-download"></i> ডাউনলোড</a>
                </div>
              </li>
              <li class="item">
                মাসিক রিপোর্ট ({{ bangla(date('F, Y')) }})
                <div class="pull-right">
                  <a href="{{ route('report.institute.monthly', $institute->device_id) }}" class="btn btn-info btn-sm"><i class="fa fa-download"></i> ডাউনলোড</a>
                </div>
              </li>
              <li class="item">
                বাৎসরিক রিপোর্ট ({{ bangla(date('Y')) }})
                <div class="pull-right">
                  <a href="#!" class="btn btn-warning btn-sm"><i class="fa fa-download"></i> ডাউনলোড</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <big>উপস্থিতি তালিকাঃ <b>{{ bangla(date('F d, Y')) }}</b>
        <div class="table-responsive">
          <table class="table table-condensed">
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
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@stop